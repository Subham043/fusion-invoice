<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FI\Modules\Invoices\Controllers;

use FI\Http\Controllers\Controller;
use FI\Modules\Clients\Models\Client;
use FI\Modules\CompanyProfiles\Models\CompanyProfile;
use FI\Modules\Groups\Models\Group;
use FI\Modules\Invoices\Models\Invoice;
use FI\Modules\Invoices\Requests\InvoiceStoreRequest;
use FI\Support\DateFormatter;
use Addons\Scheduler\Models\Schedule;
use Addons\Scheduler\Models\ScheduleOccurrence;
use Auth;
use Illuminate\Support\Facades\Storage;
use File;


class InvoiceCreateController extends Controller
{
    public function create()
    {
        return view('invoices._modal_create')
            ->with('companyProfiles', CompanyProfile::getList())
            ->with('groups', Group::getList());
    }

    public function store(InvoiceStoreRequest $request)
    {
        $input = $request->except(['client_name','invoice_date', 'event_date', 'type']);

        $input['client_id']    = Client::firstOrCreateByUniqueName($request->input('client_name'))->id;
        
        $client = Client::find($input['client_id']);
        $client->type = $request->input('type');
        $client->save();
        
        $input['invoice_date'] = DateFormatter::unformat($request->input('invoice_date'));
        $input['event_date'] = DateFormatter::unformat($request->input('event_date'));
        $input['due_at'] = DateFormatter::unformat(date("m/d/Y", strtotime($input['event_date'] . "-3 week")));
        $input['group_id'] = 1;

        $invoice = Invoice::create($input);

	
        
        $event =  new Schedule();
		$event->title       = $invoice->client->name;
		$event->description = $invoice->client->name;
		$event->quotes_id   = 0;
		$event->invoices_id   = $invoice->id;
		$event->url   = route('invoices.edit', [$invoice->id]);
		$event->category_id = 5;
		$event->user_id     = Auth::user()->id;
		$event->save();
       
        
		$occurrence = new ScheduleOccurrence();
		$occurrence->schedule_id   = $event->id;
		$occurrence->start_date = date('Y-m-d', strtotime($input['event_date']));
		$occurrence->end_date   = date('Y-m-d', strtotime($input['event_date']));
		$occurrence->save();

        return response()->json(['id' => $invoice->id], 200);
    }
}
