<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

Route::group(['middleware' => ['web', 'auth.admin'], 'namespace' => 'FI\Modules\InventoryGroupList\Controllers'], function ()
{
    Route::group(['prefix' => 'inventory-group-list'], function ()
    {
        Route::get('/', ['uses' => 'InventoryGroupListController@index', 'as' => 'inventorygrouplist.index']);
	Route::get('/barcode-print', ['uses' => 'InventoryGroupListController@barcodePrinter', 'as' => 'inventorygrouplist.barcodePrinter']);
        Route::get('create', ['uses' => 'InventoryGroupListCreateController@create', 'as' => 'inventorygrouplist.create']);
        Route::post('create', ['uses' => 'InventoryGroupListCreateController@store', 'as' => 'inventorygrouplist.store']);
        Route::get('{id}/edit', ['uses' => 'InventoryGroupListEditController@edit', 'as' => 'inventorygrouplist.edit']);
        Route::post('{id}/edit', ['uses' => 'InventoryGroupListEditController@update', 'as' => 'inventorygrouplist.update']);
        Route::get('{id}/delete', ['uses' => 'InventoryGroupListController@delete', 'as' => 'inventorygrouplist.delete']);
	Route::get('ajax/inventory_group_list_lookup', ['uses' => 'InventoryGroupListController@ajaxInventoryGroupListLookup', 'as' => 'inventorygrouplist.ajax.inventoryGroupListLookup']);

    });

	Route::group(['prefix' => 'inventory-group-list-item'], function ()
    {
        Route::post('delete', ['uses' => 'InventoryGroupListItemController@delete', 'as' => 'inventoryGroupListItem.delete']);
    });

});
