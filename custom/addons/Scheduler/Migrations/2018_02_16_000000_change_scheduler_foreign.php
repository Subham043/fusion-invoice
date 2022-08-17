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

class ChangeSchedulerForeign extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {

    	Schema::table('schedule_occurrences', function (Blueprint $table){
    		$table->dropForeign('schedule_occurrences_event_id_foreign');
	    });

	    Schema::table('schedule_occurrences', function (Blueprint $table){
		    $table->foreign('schedule_id')->references('id')->on('schedule')->onUpdate('cascade')->onDelete('cascade');
	    });

	    //clear view cache or else weird error after install
	    //Artisan::call('view:clear');
	    deleteTempFiles();
	    deleteViewCache();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	    //
    }
}
