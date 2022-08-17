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
use FI\Modules\Clients\Models\Client;
use FI\Modules\CompanyProfiles\Models\CompanyProfile;
use FI\Modules\Groups\Models\Group;
use FI\Modules\InventoryGroupList\Models\InventoryGroupList;
use FI\Modules\InventoryGroupList\Models\InventoryGroupListItem;
use FI\Modules\InventoryGroupList\Requests\InventoryGroupListStoreRequest;
use FI\Support\DateFormatter;
use Addons\Scheduler\Models\Schedule;
use Addons\Scheduler\Models\ScheduleOccurrence;
use Auth;
use FI\Modules\Currencies\Models\Currency;
use FI\Modules\TaxRates\Models\TaxRate;
use FI\Support\Statuses\InvoiceStatuses;
use FI\Traits\ReturnUrl;
use FI\Modules\Inventory\Models\Inventory;
use Illuminate\Support\Facades\Storage;
use File;


class InventoryGroupListCreateController extends Controller
{

    use ReturnUrl;

    public function create()
    {
        $inventoryList = Inventory::getList();
        $inventoryList["null"] = "Select a product";
	return view('inventorygrouplist.create')
            ->with('currencies', Currency::getList())
            ->with('inventory', $inventoryList)
            ->with('taxRates', TaxRate::getList())
		->with('itemCount', null)
            ->with('returnUrl', $this->getReturnUrl());

    }

    
	public function store(InventoryGroupListStoreRequest $request)
    {
        
        // Unformat the invoice dates.
        $invoiceInput = $request->except(['items']);
	$total = 0.0;

        // Save the invoice.
        //$invoice = Invoice::find($id);
	$invoice = InventoryGroupList::create($invoiceInput);
        //$invoice->fill($invoiceInput);
        
        //$invoice->save();     
        // Save the items.
        if($request->input('items')==null){
            return response()->json(['message' => 'Please add an item to the inventory group list'], 422);
        }
        foreach ($request->input('items') as $item)
        {
		$total = $total+$item['price'];
            $item['apply_exchange_rate'] = request('apply_exchange_rate');
            
            //$availableQuan = $item['availableQuan'];
            //unset($item['availableQuan']);
            
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
                
                $invoiceItem->fill($item);
                $invoiceItem->save();
		
            }
        }
	$invoice->total = $total;
	$invoice->save();

    }

}
