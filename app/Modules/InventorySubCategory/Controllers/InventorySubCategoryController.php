<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FI\Modules\InventorySubCategory\Controllers;

use FI\Http\Controllers\Controller;
use FI\Modules\InventoryCategory\Models\InventoryCategory;
use FI\Modules\InventorySubCategory\Models\InventorySubCategory;
use FI\Modules\InventorySubCategory\Requests\InventorySubCategoryRequest;
use FI\Modules\TaxRates\Models\TaxRate;
use FI\Support\NumberFormatter;
use Illuminate\Support\Facades\DB;

class InventorySubCategoryController extends Controller
{
    public function index()
    {
        $itemLookups = InventorySubCategory::sortable(['name' => 'asc'])->keywords(request('search'))->paginate(config('fi.resultsPerPage'));

        return view('inventorysubcategory.index')
            ->with('itemLookups', $itemLookups)
            ->with('displaySearch', true)
	    ->with('InventoryCategory', InventoryCategory::pluck( 'name', 'id' ))
            ->with('editMode', false);
    }

    public function create()
    {
        return view('inventorysubcategory.form')
		->with('InventoryCategory', InventoryCategory::pluck( 'name', 'id' ))
            ->with('editMode', false);
    }

    public function store(InventorySubCategoryRequest $request)
    {
        $inventory = InventorySubCategory::create($request->all());
        $inventory->save();

        return redirect()->route('inventory_sub_category.index')
            ->with('alertSuccess', trans('fi.record_successfully_created'));
    }

    public function edit($id)
    {
	$itemLookups = InventorySubCategory::sortable(['name' => 'asc'])->keywords(request('search'))->paginate(config('fi.resultsPerPage'));
        $itemLookup = InventorySubCategory::find($id);

        return view('inventorysubcategory.index')
            ->with('editMode', true)
	    ->with('itemLookups', $itemLookups)
            ->with('displaySearch', true)
	    ->with('InventoryCategory', InventoryCategory::pluck( 'name', 'id' ))
            ->with('itemLookup', $itemLookup);
    }

    public function update(InventorySubCategoryRequest $request, $id)
    {
        $itemLookup = InventorySubCategory::find($id);

        $itemLookup->fill($request->all());

        $itemLookup->save();

        return redirect()->route('inventory_sub_category.index')
            ->with('alertInfo', trans('fi.record_successfully_updated'));
    }

    public function delete($id)
    {
        InventorySubCategory::destroy($id);

        return redirect()->route('inventory_sub_category.index')
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
