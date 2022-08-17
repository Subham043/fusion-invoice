<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FI\Modules\BulkUpload\Controllers;

use FI\Http\Controllers\Controller;
use FI\Modules\BulkUpload\Importers\InventoryImporter;
use FI\Modules\BulkUpload\Importers\ClientImporter;
use FI\Modules\BulkUpload\Requests\BulkUploadRequest;

class BulkUploadController extends Controller
{
    public function index()
    {
        $importTypes = [
            'clients'      => trans('fi.clients'),
            //'quotes'       => trans('fi.quotes'),
            //'quoteItems'   => trans('fi.quote_items'),
            //'invoices'     => trans('fi.invoices'),
            //'invoiceItems' => trans('fi.invoice_items'),
            //'payments'     => trans('fi.payments'),
            //'expenses'     => trans('fi.expenses'),
            //'itemLookups'  => trans('fi.item_lookups'),
	    'itemLookups'  => 'Inventory',
        ];

        return view('bulkupload.index')
            ->with('importTypes', $importTypes);
    }

    public function upload(BulkUploadRequest $request)
    {
	
	$csvFile = fopen($request->file('import_file'), 'r');
	$checkHeader = fgetcsv($csvFile);
	$i=0;
	//return $checkHeader;
	if($request->import_type == 'itemLookups' && count($checkHeader) <15){
		return redirect()->route('bulkupload.index')
            	->with('alertInfo', 'The file contains invalid fields.');
	}elseif( $request->import_type == 'itemLookups' && $checkHeader[0]!="Product Name *" && $checkHeader[1]!="Item Description" && $checkHeader[2]!="Category *" && $checkHeader[3]!="Sub-Category " && $checkHeader[4]!="Location *" && $checkHeader[5]!="ItemLocator *" && $checkHeader[6]!="Quantity *" && $checkHeader[7]!="Rent Price *" && $checkHeader[8]!="Purchase Price " && $checkHeader[9]!="DateOfPurchase" && $checkHeader[10]!="Height" && $checkHeader[11]!="Length" && $checkHeader[12]!="Width" && $checkHeader[13]!="Color" && $checkHeader[14]!="Style"){
		return redirect()->route('bulkupload.index')
            	->with('alertInfo', 'The file contains invalid fields.');
	}elseif($request->import_type == 'clients' && count($checkHeader) <15){
		return redirect()->route('bulkupload.index')
            	->with('alertInfo', 'The file contains invalid fields.');
	}elseif( $request->import_type == 'clients' && $checkHeader[0]!="name *" && $checkHeader[1]!="type" && $checkHeader[2]!="address" && $checkHeader[3]!="city" && $checkHeader[4]!="state" && $checkHeader[5]!="zip" && $checkHeader[6]!="country" && $checkHeader[7]!="phone" && $checkHeader[8]!="fax" && $checkHeader[9]!="mobile *" && $checkHeader[10]!="email *" && $checkHeader[11]!="web" && $checkHeader[12]!="active" && $checkHeader[13]!="unique_name *" && $checkHeader[14]!="special"){
		return redirect()->route('bulkupload.index')
            	->with('alertInfo', 'The file contains invalid fields.');
	}

	while(($line = fgetcsv($csvFile)) !== FALSE){
		//print_r($line);
		//echo $request->import_type;
		if($request->import_type == 'itemLookups'){
			
			$inv = new InventoryImporter($line);
			$inv->importData();
		}elseif($request->import_type == 'clients'){
			$inv = new ClientImporter($line);
			$inv->importData();
		}
		
	}

	return redirect()->route('bulkupload.index')
            ->with('alertSuccess', trans('fi.record_successfully_created'));

    }

    public function mapImport($importType)
    {
        $importer = ImportFactory::create($importType);

        return view('import.map')
            ->with('importType', $importType)
            ->with('importFields', $importer->getFields($importType))
            ->with('fileFields', $importer->getFileFields(storage_path($importType . '.csv')));
    }

    public function mapImportSubmit($importType)
    {
        $importer = ImportFactory::create($importType);

        if (!$importer->validateMap(request()->all()))
        {
            return redirect()->route('import.map', [$importType])
                ->withErrors($importer->errors())
                ->withInput();
        }

        if (!$importer->importData(request()->except('_token')))
        {
            return redirect()->route('import.map', [$importType])
                ->withErrors($importer->errors());
        }

        return redirect()->route('import.index')
            ->with('alertInfo', trans('fi.records_imported_successfully'));
    }
}