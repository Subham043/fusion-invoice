<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FI\Modules\InventoryLocation\Controllers;

use FI\Http\Controllers\Controller;
use FI\Modules\InventoryLocation\Models\InventoryLocation;
use FI\Modules\InventoryLocation\Requests\InventoryLocationRequest;
use FI\Modules\TaxRates\Models\TaxRate;
use FI\Support\NumberFormatter;
use Illuminate\Support\Facades\DB;

class InventoryLocationController extends Controller
{
    public function index()
    {
        $itemLookups = InventoryLocation::sortable(['name' => 'asc'])->keywords(request('search'))->paginate(config('fi.resultsPerPage'));

        return view('inventorylocation.index')
	    ->with('editMode', false)
            ->with('itemLookups', $itemLookups)
            ->with('displaySearch', true);
    }

    public function create()
    {
        return view('inventorylocation.form')
            ->with('editMode', false);
    }

    public function store(InventoryLocationRequest $request)
    {
        $inventory = InventoryLocation::create($request->all());
        $inventory->save();

        return redirect()->route('inventory_location.index')
            ->with('alertSuccess', trans('fi.record_successfully_created'));
    }

    public function edit($id)
    {
	$itemLookups = InventoryLocation::sortable(['name' => 'asc'])->keywords(request('search'))->paginate(config('fi.resultsPerPage'));
        $itemLookup = InventoryLocation::find($id);

        return view('inventorylocation.index')
            ->with('editMode', true)
            ->with('itemLookup', $itemLookup)
	     ->with('itemLookups', $itemLookups)
            ->with('displaySearch', true);

    }

    public function update(InventoryLocationRequest $request, $id)
    {
        $itemLookup = InventoryLocation::find($id);

        $itemLookup->fill($request->all());

        $itemLookup->save();

        return redirect()->route('inventory_location.index')
            ->with('alertInfo', trans('fi.record_successfully_updated'));
    }

    public function delete($id)
    {
        InventoryLocation::destroy($id);

        return redirect()->route('inventory_location.index')
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
