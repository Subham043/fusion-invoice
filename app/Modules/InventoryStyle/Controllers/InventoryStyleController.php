<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FI\Modules\InventoryStyle\Controllers;

use FI\Http\Controllers\Controller;
use FI\Modules\InventoryStyle\Models\InventoryStyle;
use FI\Modules\InventoryStyle\Requests\InventoryStyleRequest;
use FI\Modules\TaxRates\Models\TaxRate;
use FI\Support\NumberFormatter;
use Illuminate\Support\Facades\DB;

class InventoryStyleController extends Controller
{
    public function index()
    {
        $itemLookups = InventoryStyle::sortable(['name' => 'asc'])->keywords(request('search'))->paginate(config('fi.resultsPerPage'));

        return view('inventorystyle.index')
	    ->with('editMode', false)
            ->with('itemLookups', $itemLookups)
            ->with('displaySearch', true);
    }

    public function create()
    {
        return view('inventorystyle.form')
            ->with('editMode', false);
    }

    public function store(InventoryStyleRequest $request)
    {
        $inventory = InventoryStyle::create($request->all());
        $inventory->save();

        return redirect()->route('inventory_style.index')
            ->with('alertSuccess', trans('fi.record_successfully_created'));
    }

    public function edit($id)
    {
	$itemLookups = InventoryStyle::sortable(['name' => 'asc'])->keywords(request('search'))->paginate(config('fi.resultsPerPage'));
        $itemLookup = InventoryStyle::find($id);

        return view('inventorystyle.index')
            ->with('editMode', true)
            ->with('itemLookup', $itemLookup)
	    ->with('itemLookups', $itemLookups)
            ->with('displaySearch', true);
    }

    public function update(InventoryStyleRequest $request, $id)
    {
        $itemLookup = InventoryStyle::find($id);

        $itemLookup->fill($request->all());

        $itemLookup->save();

        return redirect()->route('inventory_style.index')
            ->with('alertInfo', trans('fi.record_successfully_updated'));
    }

    public function delete($id)
    {
        InventoryStyle::destroy($id);

        return redirect()->route('inventory_style.index')
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
