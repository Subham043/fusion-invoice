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
use FI\Modules\Clients\Models\Client;
use FI\Modules\CompanyProfiles\Models\CompanyProfile;
use FI\Modules\Groups\Models\Group;
use FI\Modules\Quotes\Models\Quote;
use FI\Modules\Quotes\Requests\QuoteStoreRequest;
use FI\Support\DateFormatter;

use Addons\Scheduler\Requests\ReportRequest;
use Addons\Workorders\Models\Employee;
use Addons\Workorders\Models\Resource;
use Addons\Scheduler\Models\Schedule;
use Addons\Scheduler\Models\ScheduleReminder;
use Addons\Scheduler\Models\ScheduleOccurrence;
use Addons\Scheduler\Models\ScheduleResource;
use Addons\Scheduler\Models\Category;
use Addons\Scheduler\Models\Setting;
use Recurr;
use Recurr\Transformer;
use Recurr\Exception;
use Carbon\Carbon;
use DB;
use Auth;
use Session;
use Response;
use Illuminate\Http\Request;

class QuoteCreateController extends Controller
{
    public function create()
    {
        return view('quotes._modal_create')
            ->with('companyProfiles', CompanyProfile::getList())
            ->with('groups', Group::getList());
    }

    public function store(QuoteStoreRequest $request)
    {
        $input = $request->except(['client_name', 'type']);

        $input['client_id']  = Client::firstOrCreateByUniqueName($request->input('client_name'))->id;
        
        $client = Client::find($input['client_id']);
        $client->type = $request->input('type');
        $client->save();
        
        $input['quote_date'] = DateFormatter::unformat($input['quote_date']);
        $input['event_date'] = DateFormatter::unformat($input['event_date']);
        $input['group_id'] = 2;

        $quote = Quote::create($input);
        $client_name = Client::firstOrCreateByUniqueName($request->input('client_name'))->name;
        $event =  new Schedule();
		$event->title       = $client_name;
		$event->description = $client_name;
		$event->quotes_id   = $quote->id;
		$event->url   = route('quotes.edit', [$quote->id]);
		$event->category_id = 1;
		$event->user_id     = Auth::user()->id;
		$event->save();
       
        
		$occurrence = new ScheduleOccurrence();
		$occurrence->schedule_id   = $event->id;
		$occurrence->start_date = date('Y-m-d', strtotime($input['event_date']));
		$occurrence->end_date   = date('Y-m-d', strtotime($input['event_date']));
		$occurrence->save();



        return response()->json(['id' => $quote->id], 200);
    }
}