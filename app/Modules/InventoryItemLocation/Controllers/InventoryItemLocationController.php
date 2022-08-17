<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FI\Modules\InventoryItemLocation\Controllers;

use FI\Http\Controllers\Controller;
use FI\Modules\InventoryItemLocation\Models\InventoryItemLocation;
use FI\Modules\InventoryLocation\Models\InventoryLocation;
use FI\Modules\InventoryItemLocation\Requests\InventoryItemLocationRequest;
use FI\Modules\TaxRates\Models\TaxRate;
use FI\Support\NumberFormatter;
use Illuminate\Support\Facades\DB;

class InventoryItemLocationController extends Controller
{
    public function index()
    {
        $itemLookups = InventoryItemLocation::sortable(['name' => 'asc'])->keywords(request('search'))->paginate(config('fi.resultsPerPage'));

        return view('inventoryitemlocation.index')
            ->with('itemLookups', $itemLookups)
            ->with('displaySearch', true)
	    ->with('InventoryLocation', InventoryLocation::pluck( 'name', 'id' ))
            ->with('editMode', false);

    }

    public function create()
    {
        return view('inventoryitemlocation.form')
		->with('InventoryLocation', InventoryLocation::pluck( 'name', 'id' ))
            ->with('editMode', false);
    }

    public function store(InventoryItemLocationRequest $request)
    {
        $inventory = InventoryItemLocation::create($request->all());
        $inventory->save();

        return redirect()->route('inventory_item_location.index')
            ->with('alertSuccess', trans('fi.record_successfully_created'));
    }

    public function edit($id)
    {
	$itemLookups = InventoryItemLocation::sortable(['name' => 'asc'])->keywords(request('search'))->paginate(config('fi.resultsPerPage'));
        $itemLookup = InventoryItemLocation::find($id);

        return view('inventoryitemlocation.index')
	    ->with('itemLookups', $itemLookups)
            ->with('displaySearch', true)
            ->with('editMode', true)
	    ->with('InventoryLocation', InventoryLocation::pluck( 'name', 'id' ))
            ->with('itemLookup', $itemLookup);
    }

    public function update(InventoryItemLocationRequest $request, $id)
    {
        $itemLookup = InventoryItemLocation::find($id);

        $itemLookup->fill($request->all());

        $itemLookup->save();

        return redirect()->route('inventory_item_location.index')
            ->with('alertInfo', trans('fi.record_successfully_updated'));
    }

    public function delete($id)
    {
        InventoryItemLocation::destroy($id);

        return redirect()->route('inventory_item_location.index')
            ->with('alert', trans('fi.record_successfully_deleted'));
    }

    public function ajaxInventoryLookup()
    {
        $items = Inventory::orderBy('name')->where('name', 'like', '%' . request('query') . '%')->get();
        

        $list = [];

        foreach ($items as $item)
        {
            $list[] = [
                'id'          => $item->id,
                'name'          => $item->name,
                'description'   => $item->description,
                'price'         => NumberFormatter::format($item->price),
                'tax_rate_id'   => $item->tax_rate_id,
                'tax_rate_2_id' => $item->tax_rate_2_id,
                'total' => $item->total,
                'reserved' => $this->getReservedQuantity(request('eventDate'),request('query'),request('invoiceId'),request('quoteId')),
                'allocated' => $this->getAllocatedQuantity(request('eventDate'),request('query'),request('invoiceId')),
                'available' => $this->getAvailableQuantity(request('eventDate'),request('query'),request('invoiceId')),
            ];
        }

        return json_encode($list);
    }
    
}
