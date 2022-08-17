<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FI\Modules\Quotes\Controllers;

use FI\Http\Controllers\Controller;
use FI\Modules\CompanyProfiles\Models\CompanyProfile;
use FI\Modules\Quotes\Models\Quote;
use FI\Support\FileNames;
use FI\Support\PDF\PDFFactory;
use FI\Support\Statuses\QuoteStatuses;
use FI\Traits\ReturnUrl;
use Addons\Scheduler\Models\Schedule;
use FI\Modules\CustomFields\Models\CustomField;

class QuoteController extends Controller
{
    use ReturnUrl;

    public function index()
    {
        $this->setReturnUrl();

        $status = request('status', 'all_statuses');

        $quotes = Quote::select('quotes.*')
            ->join('clients', 'clients.id', '=', 'quotes.client_id')
            ->join('quote_amounts', 'quote_amounts.quote_id', '=', 'quotes.id')
            ->with(['client', 'activities', 'amount.quote.currency'])
            ->status($status)
            ->keywords(request('search'))
            ->clientId(request('client'))
            ->companyProfileId(request('company_profile'))
            ->sortable(['quote_date' => 'desc', 'LENGTH(number)' => 'desc', 'number' => 'desc'])
            ->paginate(config('fi.resultsPerPage'));
        
        
        
        return view('quotes.index')
            ->with('quotes', $quotes)
            ->with('status', $status)
            ->with('statuses', QuoteStatuses::listsAllFlat())
            ->with('keyedStatuses', QuoteStatuses::lists())
            ->with('companyProfiles', ['' => trans('fi.all_company_profiles')] + CompanyProfile::getList())
            ->with('displaySearch', true);
    }

    public function delete($id)
    {
        Quote::destroy($id);

	$event =  Schedule::select('*')
            ->where('quotes_id', $id)
            ->first();
	if(!empty($event)){
		$event->delete();
	}

        return redirect()->route('quotes.index')
            ->with('alert', trans('fi.record_successfully_deleted'));
    }

    public function bulkDelete()
    {
	foreach(request('ids') as $id){
            $event =  Schedule::select('*')
            ->where('quotes_id', $id)
            ->first();
		if(!empty($event)){
		    $event->delete();
		}
        }

        Quote::destroy(request('ids'));
    }

    public function bulkStatus()
    {
        Quote::whereIn('id', request('ids'))->update(['quote_status_id' => request('status')]);
	
	foreach(request('ids') as $id){
            $event =  Schedule::select('*')
            ->where('quotes_id', $id)
            ->first();
		if(!empty($event)){
    			$event->category_id = request('status');
    			$event->save();
		}
        }
    }

    public function pdf($id)
    {
        $quote = Quote::find($id);

        $pdf = PDFFactory::create();

        $pdf->download($quote->html, FileNames::quote($quote));
    }
    
    public function itemChecklist($id)
    {
        $quote = Quote::find($id);
        
        $file = view('templates.checklist.quoteChecklist')
            ->with('quote', $quote)
		->with('customFields', CustomField::forTable('quotes')->get())
            ->with('logo', $quote->companyProfile->logo())->render();

        $pdf = PDFFactory::create();

        $pdf->download($file,'item-checklist.pdf');
    }

	public function itemChecklistPrint($id)
    {
        $quote = Quote::find($id);
        
        return view('quotes.itemChecklistPrint')
            ->with('quote', $quote)
	->with('customFields', CustomField::forTable('quotes')->get())
		->with('logo', $quote->companyProfile->logo())->render();
    }

	public function barcodePrinter()
    {

        $status = request('status', 'all_statuses');

        $quotes = Quote::select('quotes.*')
            ->join('clients', 'clients.id', '=', 'quotes.client_id')
            ->join('quote_amounts', 'quote_amounts.quote_id', '=', 'quotes.id')
            ->with(['client', 'activities', 'amount.quote.currency'])
            ->paginate(config('fi.resultsPerPage'));
        
        
        
        return view('quotes.barcodePrinter')
            ->with('quotes', $quotes);
    }
}
