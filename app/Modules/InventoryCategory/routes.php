<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

Route::group(['middleware' => ['web', 'auth.admin'], 'namespace' => 'FI\Modules\InventoryCategory\Controllers'], function ()
{
    Route::get('inventory-category', ['uses' => 'InventoryCategoryController@index', 'as' => 'inventory_category.index']);
    Route::get('inventory-category/create', ['uses' => 'InventoryCategoryController@create', 'as' => 'inventory_category.create']);
    Route::get('inventory-category/{itemLookup}/edit', ['uses' => 'InventoryCategoryController@edit', 'as' => 'inventory_category.edit']);
    Route::get('inventory-category/{itemLookup}/delete', ['uses' => 'InventoryCategoryController@delete', 'as' => 'inventory_category.delete']);
    Route::get('inventory-category/ajax/inventory_lookup', ['uses' => 'InventoryCategoryController@ajaxInventoryLookup', 'as' => 'inventory_category.ajax.inventoryLookup']);

    Route::post('inventory-category', ['uses' => 'InventoryCategoryController@store', 'as' => 'inventory_category.store']);
    Route::post('inventory-category/{itemLookup}', ['uses' => 'InventoryCategoryController@update', 'as' => 'inventory_category.update']);
    Route::post('inventory-category/ajax/process', ['uses' => 'InventoryCategoryController@process', 'as' => 'inventory_category.ajax.process']);
});