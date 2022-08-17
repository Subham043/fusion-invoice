<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

Route::group(['middleware' => ['web', 'auth.admin'], 'namespace' => 'FI\Modules\InventoryColor\Controllers'], function ()
{
    Route::get('inventory-color', ['uses' => 'InventoryColorController@index', 'as' => 'inventory_color.index']);
    Route::get('inventory-color/create', ['uses' => 'InventoryColorController@create', 'as' => 'inventory_color.create']);
    Route::get('inventory-color/{itemLookup}/edit', ['uses' => 'InventoryColorController@edit', 'as' => 'inventory_color.edit']);
    Route::get('inventory-color/{itemLookup}/delete', ['uses' => 'InventoryColorController@delete', 'as' => 'inventory_color.delete']);
    Route::get('inventory-color/ajax/inventory_lookup', ['uses' => 'InventoryColorController@ajaxInventoryLookup', 'as' => 'inventory_color.ajax.inventoryLookup']);

    Route::post('inventory-color', ['uses' => 'InventoryColorController@store', 'as' => 'inventory_color.store']);
    Route::post('inventory-color/{itemLookup}', ['uses' => 'InventoryColorController@update', 'as' => 'inventory_color.update']);
    Route::post('inventory-color/ajax/process', ['uses' => 'InventoryColorController@process', 'as' => 'inventory_color.ajax.process']);
});