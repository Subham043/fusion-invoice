<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FI\Modules\Import\Controllers;

use FI\Http\Controllers\Controller;
use FI\Modules\Import\Importers\ImportFactory;
use FI\Modules\Import\Requests\ImportRequest;

class ImportController extends Controller
{
    public function index()
    {
        $importTypes = [
            'clients'      => trans('fi.clients'),
            'quotes'       => trans('fi.quotes'),
            'quoteItems'   => trans('fi.quote_items'),
            'invoices'     => trans('fi.invoices'),
            'invoiceItems' => trans('fi.invoice_items'),
            'payments'     => trans('fi.payments'),
            'expenses'     => trans('fi.expenses'),
            'itemLookups'  => trans('fi.item_lookups'),
	    'itemLookups'  => 'Inventory',
        ];

        return view('import.index')
            ->with('importTypes', $importTypes);
    }

    public function upload(ImportRequest $request)
    {
        $request->file('import_file')->move(storage_path(), $request->input('import_type') . '.csv');

        return redirect()->route('import.map', [$request->input('import_type')]);
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