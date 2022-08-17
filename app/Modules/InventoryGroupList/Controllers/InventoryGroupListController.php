<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FI\Modules\InventoryGroupList\Controllers;

use FI\Http\Controllers\Controller;
use FI\Modules\CompanyProfiles\Models\CompanyProfile;
use FI\Modules\InventoryGroupList\Models\InventoryGroupList;
use FI\Support\FileNames;
use FI\Support\PDF\PDFFactory;
use FI\Support\Statuses\InvoiceStatuses;
use FI\Traits\ReturnUrl;
use Addons\Scheduler\Models\Schedule;
use FI\Modules\CustomFields\Models\CustomField;

class InventoryGroupListController extends Controller
{
    use ReturnUrl;

    public function index()
    {
        $this->setReturnUrl();

        $status = request('status', 'all_statuses');

        $invoices = InventoryGroupList::select('inventory_group_list.*')
           // ->status($status)
            ->keywords(request('search'))
            ->paginate(config('fi.resultsPerPage'));

        return view('inventorygrouplist.index')
            ->with('invoices', $invoices)
		->with('status', $status)
            ->with('displaySearch', true);
    }

	public function barcodePrinter()
    {
        
        $invoices = InventoryGroupList::select('inventory_group_list.*')
            ->keywords(request('search'))
            ->paginate(config('fi.resultsPerPage'));

        return view('inventorygrouplist.barcodePrinter')
            ->with('invoices', $invoices)
            ->with('displaySearch', true);
    }

    public function delete($id)
    {

        InventoryGroupList::destroy($id);

        return redirect()->route('inventorygrouplist.index')
            ->with('alert', trans('fi.record_successfully_deleted'));
    }

    public function bulkDelete()
    {
	foreach(request('ids') as $id){
            $invoice = Invoice::find($id);
            
        
            if($invoice->quote()->count()){
                
                $event =  Schedule::select('*')
                    ->where('quotes_id', $invoice->quote->id)
                    ->first();
                    if(!empty($event)){
        		        $event->url   = route('quotes.edit', [$invoice->quote->id]);
		                $event->category_id = $invoice->quote->quote_status_id;
		                $event->invoices_id = 0;
		                $event->save();
                    }
            }else{
       
                $event =  Schedule::select('*')
                    ->where('invoices_id', $invoice->id)
                    ->first();
                    if(!empty($event)){
        		$event->delete();
                    }
            }
            
        }
        Invoice::destroy(request('ids'));
    }

    public function bulkStatus()
    {
        Invoice::whereIn('id', request('ids'))
            ->where('invoice_status_id', '<>', InvoiceStatuses::getStatusId('paid'))
            ->update(['invoice_status_id' => request('status')]);
	foreach(request('ids') as $id){
            $invoice = Invoice::find($id);
            if($invoice->quote()->count()){
                $quote = Quote::select('*')
                    ->where('invoice_id', $id)
                    ->first();
                $event =  Schedule::select('*')
                    ->where('quotes_id', $quote->id)
                    ->first();
			if(!empty($event)){
        		$event->category_id = number_format(request('status'))+4;
        		$event->save();
			}
            }else{
                $event =  Schedule::select('*')
                    ->where('invoices_id', $id)
                    ->first();
			if(!empty($event)){
        		$event->category_id = number_format(request('status'))+4;
        		$event->save();
			}
            }
        }
    }

    public function pdf($id)
    {
        $invoice = Invoice::find($id);

        $pdf = PDFFactory::create();

        $pdf->download($invoice->html, FileNames::invoice($invoice));
    }
    
    public function itemChecklist($id)
    {
        $invoice = Invoice::find($id);
        
        $file = view('templates.checklist.invoiceChecklist')
            ->with('invoice', $invoice)
	   ->with('customFields', CustomField::forTable('invoices')->get())
            ->with('logo', $invoice->companyProfile->logo())->render();

        $pdf = PDFFactory::create();

        $pdf->download($file,'item-checklist.pdf');
    }
    
    public function print($id)
    {
        $invoice = Invoice::find($id);

        
        return view('invoices.print')
            ->with('invoice', $invoice);
    }

	public function itemChecklistPrint($id)
	{
		$invoice = Invoice::find($id);
//		return $invoice->custom->column_9;
		return view('invoices.itemChecklistPrint')
			->with('invoice', $invoice)
			->with('customFields', CustomField::forTable('invoices')->get())
			->with('logo', $invoice->companyProfile->logo())->render();
	}

	public function ajaxInventoryGroupListLookup()
    {
        $items = InventoryGroupList::orderBy('name')->where('name', 'like', '%' . request('query') . '%')->get();
        

        $list = [];

        foreach ($items as $item)
        {
            $list[] = [
                'id'          => $item->id,
                'name'          => $item->name,
                'description'   => $item->summary,
                'total' => $item->total,
            ];
        }

        return json_encode($list);
    }


}
