<?php

/**
 * This file is part of Scheduler Addon for FusionInvoice.
 * (c) Cytech <cytech@cytech-eng.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace Addons\Scheduler\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
Use Carbon\Carbon;
use FI\Modules\Quotes\Models\Quote;
use FI\Modules\Invoices\Models\Invoice;


class Schedule extends Model {
   use SoftDeletes;

    protected $dates = ['deleted_at'];

	protected $guarded = ['id'];

    protected $table = 'schedule';

    public $timestamps = true;

	//necessary here for scope below
	public function getStartDateAttribute() {
		return Carbon::parse( $this->attributes['start_date'] )->format( 'Y-m-d H:i' );
	}

	public function getEndDateAttribute() {
		return Carbon::parse( $this->attributes['end_date'] )->format( 'Y-m-d H:i' );
	}

	public function scopeWithOccurrences($query){
		$query->leftjoin('schedule_occurrences','schedule.id', '=',
			'schedule_occurrences.schedule_id');
	}

    public function category()
    {
        return $this->hasOne('Addons\Scheduler\Models\Category', 'id', 'category_id');
    }

    public function occurrences()
    {
        return $this->hasMany('Addons\Scheduler\Models\ScheduleOccurrence', 'schedule_id', 'id');
    }

    public function reminders()
    {
        return $this->hasMany('Addons\Scheduler\Models\ScheduleReminder', 'schedule_id', 'id');
    }

    public function resources()
    {
        return $this->hasMany('Addons\Scheduler\Models\ScheduleResource','schedule_id', 'id');
    }
    
    public function quotes()
    {
        return $this->hasOne('FI\Modules\Quotes\Models\Quote','id', 'quotes_id');
    }
    
    public function invoices()
    {
        return $this->hasOne('FI\Modules\Invoices\Models\Invoice','id', 'invoices_id');
    }
    
    public function getNum()
    {
        if($this->invoices()->count()>0 || $this->quotes()->count()>0){
            return $this->quotes_id==0 ? $this->invoices->number : $this->category_id>4 ? $this->invoices->number : $this->quotes->number;
        }
        return null;
    }

}
