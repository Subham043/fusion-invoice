<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

Route::group(['middleware' => ['web', 'auth.admin'], 'namespace' => 'FI\Modules\InventorySubCategory\Controllers'], function ()
{
    Route::get('inventory-sub-category', ['uses' => 'InventorySubCategoryController@index', 'as' => 'inventory_sub_category.index']);
    Route::get('inventory-sub-category/create', ['uses' => 'InventorySubCategoryController@create', 'as' => 'inventory_sub_category.create']);
    Route::get('inventory-sub-category/{itemLookup}/edit', ['uses' => 'InventorySubCategoryController@edit', 'as' => 'inventory_sub_category.edit']);
    Route::get('inventory-sub-category/{itemLookup}/delete', ['uses' => 'InventorySubCategoryController@delete', 'as' => 'inventory_sub_category.delete']);
    Route::get('inventory-sub-category/ajax/inventory_lookup', ['uses' => 'InventorySubCategoryController@ajaxInventoryLookup', 'as' => 'inventory_sub_category.ajax.inventoryLookup']);

    Route::post('inventory-sub-category', ['uses' => 'InventorySubCategoryController@store', 'as' => 'inventory_sub_category.store']);
    Route::post('inventory-sub-category/{itemLookup}', ['uses' => 'InventorySubCategoryController@update', 'as' => 'inventory_sub_category.update']);
    Route::post('inventory-sub-category/ajax/process', ['uses' => 'InventorySubCategoryController@process', 'as' => 'inventory_sub_category.ajax.process']);
});