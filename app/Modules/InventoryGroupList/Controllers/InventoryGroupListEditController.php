<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FI\Modules\InventoryGroupList\Controllers;

use FI\Http\Controllers\Controller;
use FI\Modules\Currencies\Models\Currency;
use FI\Modules\Inventory\Models\Inventory;
use FI\Modules\CustomFields\Models\CustomField;
use FI\Modules\InventoryGroupList\Models\InventoryGroupList;
use FI\Modules\InventoryGroupList\Models\InventoryGroupListItem;
use FI\Modules\Invoices\Support\InvoiceTemplates;
use FI\Modules\InventoryGroupList\Requests\InventoryGroupListUpdateRequest;
use FI\Modules\ItemLookups\Models\ItemLookup;
use FI\Modules\TaxRates\Models\TaxRate;
use FI\Support\DateFormatter;
use FI\Support\Statuses\InvoiceStatuses;
use FI\Traits\ReturnUrl;
use Addons\Scheduler\Models\Schedule;
use FI\Modules\Quotes\Models\Quote;
use Addons\Scheduler\Models\ScheduleOccurrence;
use Illuminate\Support\Facades\Storage;
use File;


class InventoryGroupListEditController extends Controller
{
    use ReturnUrl;

    public function edit($id)
    {
        $inventoryList = Inventory::getList();
        $inventoryList["null"] = "Select a product";
        $invoice = InventoryGroupList::with(['items'])->find($id);
//echo "<pre>"; print_r($invoice);exit;
        return view('inventorygrouplist.edit')
            ->with('invoice', $invoice)
            ->with('inventory', $inventoryList)
            ->with('taxRates', TaxRate::getList())
		->with('itemCount', true)
            ->with('returnUrl', $this->getReturnUrl());
    }

    public function update(InventoryGroupListUpdateRequest $request, $id)
    {
        
        // Unformat the invoice dates.
        $invoiceInput                 = $request->except(['items']);
	$total = 0.0;

        // Save the invoice.
        $invoice = InventoryGroupList::find($id);
        $invoice->fill($invoiceInput);
	$invoice->save();
        
        // Save the items.
        if($request->input('items')==null){
            return response()->json(['message' => 'Please add an item to the invoice'], 422);
        }
        foreach ($request->input('items') as $item)
        {
            $total = $total+$item['price'];
            
            $inventory = Inventory::find($item['inventory_id']);

            if (!isset($item['id']) or (!$item['id']))
            {
                                
                $saveItemAsLookup = $item['save_item_as_lookup'];
                unset($item['save_item_as_lookup']);

                InventoryGroupListItem::create([
                        'name'          => $item['name'],
                        'description'   => $item['description'],
                        'price'         => $item['price'],
                        'inventory_id' => $item['inventory_id'],
			'quantity' => $item['quantity'],
			'inventory_group_list_id' => $invoice->id,
                    ]);

            }
            else
            {
                                
                $invoiceItem = InventoryGroupListItem::find($item['id']);
                
                $invoiceItem->fill([
                        'name'          => $item['name'],
                        'description'   => $item['description'],
                        'price'         => $item['price'],
                        'inventory_id' => $item['inventory_id'],
			'quantity' => $item['quantity'],
			'inventory_group_list_id' => $invoice->id,
                    ]);
                $invoiceItem->save();
            }
        }
	$invoice->total = $total;
	$invoice->save();

	$url = "GRP-".$invoice->id;

	$barcode = new \FI\Modules\Inventory\Barcode\Barcode();
	$bobj = $barcode->getBarcodeObj('C128', $url, 0, -30, 'black', array(0, 0, 0, 0));
  	Storage::put('public/barcode/inventory-group-list-'.$invoice->id.'-barcode.png', $bobj->getPngData());
	File::move(storage_path('app/public/barcode/inventory-group-list-'.$invoice->id.'-barcode.png'), public_path('assets/barcode/inventory-group-list-'.$invoice->id.'-barcode.png'));


    }

    public function refreshEdit($id)
    {
	$inventoryList = Inventory::getList();
        $inventoryList["null"] = "Select a product";
        $invoice = Invoice::with(['items.amount.item.invoice.currency'])->find($id);

        return view('invoices._edit')
            ->with('invoice', $invoice)
            ->with('statuses', InvoiceStatuses::lists())
            ->with('currencies', Currency::getList())
            ->with('inventory', $inventoryList)
            ->with('taxRates', TaxRate::getList())
            ->with('customFields', CustomField::forTable('invoices')->orderBy('column_name', 'DESC')->get())
            ->with('returnUrl', $this->getReturnUrl())
            ->with('templates', InvoiceTemplates::lists())
            ->with('itemCount', count($invoice->invoiceItems));
            
    }

    public function refreshTotals()
    {
        return view('invoices._edit_totals')
            ->with('invoice', Invoice::with(['items.amount.item.invoice.currency'])->find(request('id')));
    }

    public function refreshTo()
    {
        return view('invoices._edit_to')
            ->with('invoice', Invoice::find(request('id')));
    }

    public function refreshFrom()
    {
        return view('invoices._edit_from')
            ->with('invoice', Invoice::find(request('id')));
    }

    public function updateClient()
    {
        Invoice::where('id', request('id'))->update(['client_id' => request('client_id')]);
    }

    public function updateCompanyProfile()
    {
        Invoice::where('id', request('id'))->update(['company_profile_id' => request('company_profile_id')]);
    }
}
