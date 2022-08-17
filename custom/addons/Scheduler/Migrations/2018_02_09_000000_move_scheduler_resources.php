<?php
/**
 * *
 *  * This file is part of Scheduler Addon for FusionInvoice.
 *  * (c) Cytech <cytech@cytech-eng.com>
 *  *
 *  * For the full copyright and license information, please view the LICENSE
 *  * file that was distributed with this source code.
 *  
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Addons\Scheduler\Models\Schedule;

class MoveSchedulerResources extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {

	    //only employees was ever used in resource_table
	    $oldresources = Schedule::where( 'resource_table', 'employees' )->get();

	    foreach ( $oldresources as $resource ) {
		    DB::table( 'schedule_resources' )->insert( [
			    'id'             => $resource->id,
			    'schedule_id'    => $resource->id,
			    'fid'            => 2,
			    'resource_table' => 'employees',
			    'resource_id'    => $resource->resource_id,
			    'value'          => null,
			    'qty'            => 1
		    ] );
	    }

	    //remove transferred columns from schedule table
	    Schema::table( 'schedule', function ( Blueprint $table ) {
		    $table->dropColumn( [ 'resource_table', 'resource_id' ] );
	    } );

	    //clear view cache or else weird error after install
	    Artisan::call('view:clear');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
