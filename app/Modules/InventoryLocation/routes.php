<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

Route::group(['middleware' => ['web', 'auth.admin'], 'namespace' => 'FI\Modules\InventoryLocation\Controllers'], function ()
{
    Route::get('inventory-location', ['uses' => 'InventoryLocationController@index', 'as' => 'inventory_location.index']);
    Route::get('inventory-location/create', ['uses' => 'InventoryLocationController@create', 'as' => 'inventory_location.create']);
    Route::get('inventory-location/{itemLookup}/edit', ['uses' => 'InventoryLocationController@edit', 'as' => 'inventory_location.edit']);
    Route::get('inventory-location/{itemLookup}/delete', ['uses' => 'InventoryLocationController@delete', 'as' => 'inventory_location.delete']);
    Route::get('inventory-location/ajax/inventory_lookup', ['uses' => 'InventoryLocationController@ajaxInventoryLookup', 'as' => 'inventory_location.ajax.inventoryLookup']);

    Route::post('inventory-location', ['uses' => 'InventoryLocationController@store', 'as' => 'inventory_location.store']);
    Route::post('inventory-location/{itemLookup}', ['uses' => 'InventoryLocationController@update', 'as' => 'inventory_location.update']);
    Route::post('inventory-location/ajax/process', ['uses' => 'InventoryLocationController@process', 'as' => 'inventory_location.ajax.process']);
});