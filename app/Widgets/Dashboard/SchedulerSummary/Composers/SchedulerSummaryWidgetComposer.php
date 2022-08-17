<?php

/**
 * This file is part of Scheduler Addon for FusionInvoice.
 * (c) Cytech <cytech@cytech-eng.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FI\Widgets\Dashboard\SchedulerSummary\Composers;

use FI\Modules\Addons\Models\Addon;
use Illuminate\Support\Facades\DB;
use Addons\Scheduler\Models\ScheduleReminder;
use Addons\Scheduler\Models\ScheduleOccurrence;
use Carbon\Carbon;

class SchedulerSummaryWidgetComposer
{
    public function compose($view)
    {
    	$addon = Addon::where('name', 'Scheduler')->firstOrFail();

    	if (!$addon->has_pending_migrations) {

		    $view->with( 'schedulerEvents', $this->getSchedulerEvents() );

	    }else{
    		$data = [
    			'monthEvent' => 'Run Upgrade',
			    'lastMonthEvent' => 'Run Upgrade',
			    'nextMonthEvent' => 'Run Upgrade',
			    'fullMonthEvent' => '',
			    'fullYearMonthEvent' => '',
			    'reminders' => 'Run Upgrade',
		    ];
		    $view->with( 'schedulerEvents', $data );
	    }

    }

    public function getSchedulerEvents()
    {


	        $today = new Carbon();

		    $data['monthEvent'] = ScheduleOccurrence::where( 'start_date', '>=', $today->copy()->modify( '0:00 first day of this month' ) )
		                                            ->where( 'start_date', '<=', $today->copy()->modify( '23:59:59 last day of this month' ) )
		                                            ->count();

		    $data['lastMonthEvent'] = ScheduleOccurrence::where( 'start_date', '>=', $today->copy()->modify( '0:00 first day of last month' ) )
		                                                ->where( 'start_date', '<=', $today->copy()->modify( '23:59:59 last day of last month' ) )
		                                                ->count();

		    $data['nextMonthEvent'] = ScheduleOccurrence::where( 'start_date', '>=', $today->copy()->modify( '0:00 first day of next month' ) )
		                                                ->where( 'start_date', '<=', $today->copy()->modify( '23:59:59 last day of next month' ) )
		                                                ->count();

		    $data['fullMonthEvent'] = ScheduleOccurrence::select( DB::raw( "count('id') as total,start_date" ) )
		                                                ->where( 'start_date', '>=', date( 'Y-m-01' ) )
		                                                ->where( 'start_date', '<=', date( 'Y-m-t' ) )
		                                                ->groupBy( DB::raw( "DATE_FORMAT(start_date, '%Y%m%d')" ) )
		                                                ->get();

		    $data['fullYearMonthEvent'] = ScheduleOccurrence::select( DB::raw( "count('id') as total,start_date" ) )
		                                                    ->where( 'start_date', '>=', date( 'Y-01-01' ) )
		                                                    ->where( 'start_date', '<=', date( 'Y-12-31' ) )
		                                                    ->groupBy( DB::raw( "DATE_FORMAT(start_date, '%Y%m')" ) )
		                                                    ->get();

		    $data['reminders'] = ScheduleReminder::with( 'Schedule', 'Schedule.occurrences' )->where( 'reminder_date', '>=', $today->copy()->modify( '0:00' ) )->get();

		    return $data;

    }

}