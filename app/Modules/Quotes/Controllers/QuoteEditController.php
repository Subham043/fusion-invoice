<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FI\Modules\Quotes\Controllers;

use FI\Http\Controllers\Controller;
use FI\Modules\Currencies\Models\Currency;
use FI\Modules\CustomFields\Models\CustomField;
use FI\Modules\ItemLookups\Models\ItemLookup;
use FI\Modules\Quotes\Models\Quote;
use FI\Modules\Quotes\Models\QuoteItem;
use FI\Modules\Quotes\Support\QuoteTemplates;
use FI\Modules\Quotes\Requests\QuoteUpdateRequest;
use FI\Modules\TaxRates\Models\TaxRate;
use FI\Support\DateFormatter;
use FI\Support\NumberFormatter;
use FI\Support\Statuses\QuoteStatuses;
use FI\Traits\ReturnUrl;
use Addons\Scheduler\Models\Schedule;
use Addons\Scheduler\Models\ScheduleOccurrence;
use FI\Modules\Inventory\Models\Inventory;
use FI\Modules\Invoices\Models\Invoice;
use FI\Modules\InventoryGroupList\Models\InventoryGroupList;
use FI\Modules\Quotes\Models\QuoteGroupItem;
use Illuminate\Support\Facades\Storage;
use File;


class QuoteEditController extends Controller
{
    use ReturnUrl;

    public function edit($id)
    {
	$inventoryList = Inventory::getList();
        $inventoryList["null"] = "Select a product";
	$inventory_group_list = InventoryGroupList::getList();
	$inventory_group_list["null"] = "Select a group";

        $quote = Quote::with(['items.amount.item.quote.currency'])->find($id);
        
        // return $quote->getReservedQuantity('chair');

        return view('quotes.edit')
            ->with('quote', $quote)
            ->with('statuses', QuoteStatuses::lists())
            ->with('currencies', Currency::getList())
	    ->with('inventory_group_list', $inventory_group_list)
            ->with('inventory', $inventoryList)
            ->with('taxRates', TaxRate::getList())
            ->with('customFields', CustomField::forTable('quotes')->orderBy('order_by', 'ASC')->get())
            ->with('returnUrl', $this->getReturnUrl())
            ->with('templates', QuoteTemplates::lists())
            ->with('itemCount', count($quote->quoteItems));
    }

    public function update(QuoteUpdateRequest $request, $id)
    {
        // Unformat the quote dates.
        $input               = $request->except(['items', 'group_items', 'custom', 'apply_exchange_rate']);
        
        $input['quote_date'] = DateFormatter::unformat($input['quote_date']);
        $input['event_date'] = DateFormatter::unformat($input['event_date']);
        $input['expires_at'] = DateFormatter::unformat($input['expires_at']);

        // Save the quote.
        $quote = Quote::find($id);
        $exist_quote = Quote::where('client_id',$quote->client_id)->where('event_date',$input['event_date'])->where('id', '<>', $quote->id)->get();
       // if(count($exist_quote)>0){
         //    return response()->json(['message' => 'Another event exists on the same date by the same client'], 422);
        //}
       
        $quote->fill($input);
        $quote->save();

        
        // save the event color
        $event =  Schedule::select('*')
            ->where('quotes_id', $id)
            ->first();
		$event->category_id = $input['quote_status_id'];
		$event->save();
		
		// save the event date
		$occurrence = ScheduleOccurrence::select('*')
            ->where('schedule_id', $event->id)
            ->first();
		$occurrence->start_date = date('Y-m-d', strtotime($input['event_date']));
		$occurrence->end_date   = date('Y-m-d', strtotime($input['event_date']));
		$occurrence->save();
		
		if($quote->invoice_id!=0){
		$invoice = Invoice::find($quote->invoice_id);
		$invoice->event_date = $input['event_date'];
		$invoice->save();
		}


        // Save the custom fields.
            $quote->custom->update($request->input('custom', []));

        // Save the items.
        if($request->input('items')==null){
            return response()->json(['message' => 'Please add an item to the quote'], 422);
        }
        foreach ($request->input('items') as $item)
        {
            $item['apply_exchange_rate'] = $request->input('apply_exchange_rate');
            
            $availableQuan = $item['availableQuan'];
            unset($item['availableQuan']);
            
            $inventory = Inventory::find($item['inventory_id']);
            
            

            if (!isset($item['id']) or (!$item['id']))
            {
                
                if((int)$item['quantity']>(int)$availableQuan){
                    return response()->json(['message' => $item['name']."'s quantity is more than the available quantity"], 400);
                }
                    
                $saveItemAsLookup = $item['save_item_as_lookup'];
                unset($item['save_item_as_lookup']);

                QuoteItem::create($item);
                

                if ($saveItemAsLookup)
                {
                    ItemLookup::create([
                        'name'          => $item['name'],
                        'description'   => $item['description'],
                        'price'         => $item['price'],
                        'tax_rate_id'   => $item['tax_rate_id'],
                        'tax_rate_2_id' => $item['tax_rate_2_id'],
                        'inventory_id' => $item['inventory_id'],
                    ]);
                }
            }
            else
            {
                if((int)$item['quantity']>(int)$availableQuan){
                    return response()->json(['message' => $item['name']."'s quantity is more than the available quantity"], 400);
                }
                
                $quoteItem = QuoteItem::find($item['id']);
                
                $quoteItem->fill($item);
                $quoteItem->save();
            }
        }
	
	if(!empty($request->input('group_items'))){

	foreach ($request->input('group_items') as $item)
        {
                        
            $inventory = InventoryGroupList::find($item['inventory_group_list_id']);

            if (!isset($item['id']) or (!$item['id']))
            {

                $test = QuoteGroupItem::create([
                        'name'          => $item['group_name'],
                        'description'   => $item['group_description'],
                        'price'         => $item['group_price'],
			'total'         => $item['group_price']*$item['group_quantity'],
			'quantity'         => $item['group_quantity'],
			'display_order'         => $item['display_order'],
			'quote_id'         => $item['quote_id'],
                        'inventory_group_list_id' => $item['inventory_group_list_id'],
                    ]);
            }
            else
            {                
                $invoiceItem = QuoteGroupItem::find($item['id']);
                
                $invoiceItem->fill([
			'name'          => $item['group_name'],
                        'description'   => $item['group_description'],
                        'price'         => $item['group_price'],
			'total'         => $item['group_price']*$item['group_quantity'],
			'quantity'         => $item['group_quantity'],
			'display_order'         => $item['display_order'],
			'quote_id'         => $item['quote_id'],
                        'inventory_group_list_id' => $item['inventory_group_list_id'],
                    ]);
                $invoiceItem->save();
            }
        }
	}

	
	$url = "CHK-".$quote->id;

	$barcode = new \FI\Modules\Inventory\Barcode\Barcode();
	$bobj = $barcode->getBarcodeObj('C128', $url, 0, -30, 'black', array(0, 0, 0, 0));
  	Storage::put('public/barcode/quote-item-checklist-'.$quote->id.'-barcode.png', $bobj->getPngData());
	File::move(storage_path('app/public/barcode/quote-item-checklist-'.$quote->id.'-barcode.png'), public_path('assets/barcode/quote-item-checklist-'.$quote->id.'-barcode.png'));

        return response()->json(['success' => true], 200);
    }

    public function refreshEdit($id)
    {
	$inventoryList = Inventory::getList();
        $inventoryList["null"] = "Select a product";
	$inventory_group_list = InventoryGroupList::getList();
	$inventory_group_list["null"] = "Select a group";

        $quote = Quote::with(['items.amount.item.quote.currency'])->find($id);

        return view('quotes._edit')
            ->with('quote', $quote)
            ->with('statuses', QuoteStatuses::lists())
            ->with('currencies', Currency::getList())
            ->with('inventory', $inventoryList)
	    ->with('inventory_group_list', $inventory_group_list)
            ->with('taxRates', TaxRate::getList())
            ->with('customFields', CustomField::forTable('quotes')->orderBy('order_by', 'ASC')->get())
            ->with('returnUrl', $this->getReturnUrl())
            ->with('templates', QuoteTemplates::lists())
            ->with('itemCount', count($quote->quoteItems));
    }

    public function refreshTotals()
    {
        return view('quotes._edit_totals')
            ->with('quote', Quote::with(['items.amount.item.quote.currency'])->find(request('id')));
    }

    public function refreshTo()
    {
        return view('quotes._edit_to')
            ->with('quote', Quote::find(request('id')));
    }

    public function refreshFrom()
    {
        return view('quotes._edit_from')
            ->with('quote', Quote::find(request('id')));
    }

    public function updateClient()
    {
        Quote::where('id', request('id'))->update(['client_id' => request('client_id')]);
    }

    public function updateCompanyProfile()
    {
        Quote::where('id', request('id'))->update(['company_profile_id' => request('company_profile_id')]);
    }
}
