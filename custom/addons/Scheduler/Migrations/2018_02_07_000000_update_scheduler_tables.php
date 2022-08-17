<?php

use Illuminate\Database\Migrations\Migration;

class UpdateSchedulerTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    	// delete unused start_date and start_date columns
		Schema::table('schedule', function ($table){
			$table->dropColumn('start_date');
			$table->dropColumn('end_date');
			$table->dropColumn('created_by');
			$table->dropColumn('updated_by');
		});

	    Schema::table('schedule_occurrences', function ($table){
		    $table->renameColumn('event_id','schedule_id');
	    	$table->renameColumn('start_time','start_date');
		    $table->renameColumn('end_time','end_date');
	    });

	    Schema::rename('schedule_reminder', 'schedule_reminders');

	    Schema::table('schedule_reminders', function ($table){
		    $table->dropColumn('deleted_at');
		    $table->dropColumn('created_by');
		    $table->dropColumn('updated_by');
	    });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	    Schema::rename('schedule_reminders', 'schedule_reminder');
    }
}
