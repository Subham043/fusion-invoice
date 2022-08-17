<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

Route::group(['middleware' => ['web', 'auth.admin'], 'namespace' => 'FI\Modules\BulkUpload\Controllers'], function ()
{
    Route::get('bulk-upload', ['uses' => 'BulkUploadController@index', 'as' => 'bulkupload.index']);
    Route::get('bulk-upload/map/{import_type}', ['uses' => 'BulkUploadController@mapImport', 'as' => 'bulkupload.map']);

    Route::post('bulk-upload/upload', ['uses' => 'BulkUploadController@upload', 'as' => 'bulkupload.upload']);
    Route::post('bulk-upload/map/{import_type}', ['uses' => 'BulkUploadController@mapImportSubmit', 'as' => 'bulkupload.map.submit']);
});