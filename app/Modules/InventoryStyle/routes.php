<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

Route::group(['middleware' => ['web', 'auth.admin'], 'namespace' => 'FI\Modules\InventoryStyle\Controllers'], function ()
{
    Route::get('inventory-style', ['uses' => 'InventoryStyleController@index', 'as' => 'inventory_style.index']);
    Route::get('inventory-style/create', ['uses' => 'InventoryStyleController@create', 'as' => 'inventory_style.create']);
    Route::get('inventory-style/{itemLookup}/edit', ['uses' => 'InventoryStyleController@edit', 'as' => 'inventory_style.edit']);
    Route::get('inventory-style/{itemLookup}/delete', ['uses' => 'InventoryStyleController@delete', 'as' => 'inventory_style.delete']);
    Route::get('inventory-style/ajax/inventory_lookup', ['uses' => 'InventoryStyleController@ajaxInventoryLookup', 'as' => 'inventory_style.ajax.inventoryLookup']);

    Route::post('inventory-style', ['uses' => 'InventoryStyleController@store', 'as' => 'inventory_style.store']);
    Route::post('inventory-style/{itemLookup}', ['uses' => 'InventoryStyleController@update', 'as' => 'inventory_style.update']);
    Route::post('inventory-style/ajax/process', ['uses' => 'InventoryStyleController@process', 'as' => 'inventory_style.ajax.process']);
});