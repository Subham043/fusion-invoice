<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

Route::group(['middleware' => ['web', 'auth.admin'], 'namespace' => 'FI\Modules\InventoryItemLocation\Controllers'], function ()
{
    Route::get('inventory-item-location', ['uses' => 'InventoryItemLocationController@index', 'as' => 'inventory_item_location.index']);
    Route::get('inventory-item-location/create', ['uses' => 'InventoryItemLocationController@create', 'as' => 'inventory_item_location.create']);
    Route::get('inventory-item-location/{itemLookup}/edit', ['uses' => 'InventoryItemLocationController@edit', 'as' => 'inventory_item_location.edit']);
    Route::get('inventory-item-location/{itemLookup}/delete', ['uses' => 'InventoryItemLocationController@delete', 'as' => 'inventory_item_location.delete']);
    Route::get('inventory-item-location/ajax/inventory_lookup', ['uses' => 'InventoryItemLocationController@ajaxInventoryLookup', 'as' => 'inventory_item_location.ajax.inventoryLookup']);

    Route::post('inventory-item-location', ['uses' => 'InventoryItemLocationController@store', 'as' => 'inventory_item_location.store']);
    Route::post('inventory-item-location/{itemLookup}', ['uses' => 'InventoryItemLocationController@update', 'as' => 'inventory_item_location.update']);
    Route::post('inventory-item-location/ajax/process', ['uses' => 'InventoryItemLocationController@process', 'as' => 'inventory_item_location.ajax.process']);
});