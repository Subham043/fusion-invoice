<?php

/**
 * This file is part of Scheduler Addon for FusionInvoice.
 * (c) Cytech <cytech@cytech-eng.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SchedulerAddonInstall extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedule', function (Blueprint $table)
        {
            $table->increments('id');
            $table->string('title',150);
            $table->text('description')->nullable();
            $table->tinyInteger('isRecurring')->unsigned()->default(0);
            $table->string('rrule',300)->nullable();
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->integer('user_id');
            $table->integer('category_id')->default(1);
            $table->integer('quotes_id')->default(0);
            $table->integer('invoices_id')->default(0);
            $table->string('resource_table',45)->nullable();
            $table->integer('resource_id')->nullable();
            $table->integer('created_by');
            $table->integer('updated_by');
            $table->string('url',300)->nullable();
            $table->boolean('will_call');
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
        });

        Schema::create('schedule_occurrences', function (Blueprint $table)
        {
            $table->increments('oid');
            $table->integer('event_id')->length(10)->unsigned();
            $table->dateTime('start_time')->nullable();
            $table->dateTime('end_time')->nullable();
            $table->timestamps();
            $table->index('event_id', 'schedule_occurrence_event_id_foreign');
        });

        Schema::table('schedule_occurrences', function (Blueprint $table){
            $table->foreign('event_id')->references('id')->on('schedule')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('schedule_resources', function (Blueprint $table)
        {
            $table->increments('id');
            $table->integer('schedule_id')->length(10)->unsigned();
            $table->integer('fid');
            $table->string('resource_table',45)->nullable();
            $table->integer('resource_id')->nullable();
            $table->string('value')->nullable();
            $table->integer('qty')->nullable();
            $table->index('schedule_id', 'schedule_resource_schedule_id_foreign');
        });

        Schema::table('schedule_resources', function (Blueprint $table){
            $table->foreign('schedule_id')->references('id')->on('schedule')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('schedule_reminder', function (Blueprint $table)
        {
            $table->increments('id');
            $table->integer('schedule_id')->length(10)->unsigned();
            $table->timestamp('reminder_date')->default('0000-00-00 00:00:00');
            $table->text('reminder_location');
            $table->longText('reminder_text');
            $table->integer('created_by');
            $table->integer('updated_by');
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
            $table->index('schedule_id', 'schedule_reminder_schedule_id_foreign');


        });

        Schema::table('schedule_reminder', function (Blueprint $table){
        $table->foreign('schedule_id')->references('id')->on('schedule')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('schedule_categories', function (Blueprint $table)
        {
            $table->increments('id');
            $table->string('name',50);
            $table->string('text_color',10);
            $table->string('bg_color',10);

        });

        DB::table('schedule_categories')->insert(array('name' => 'Quotes-Draft','text_color' => '#ffffff','bg_color' => '#605ca8'));
        DB::table('schedule_categories')->insert(array('name' => 'Quotes-Sent','text_color' => '#ffffff','bg_color' => '#3d9970'));
        DB::table('schedule_categories')->insert(array('name' => 'Quotes-Approved','text_color' => '#ffffff','bg_color' => '#0073b7'));
        DB::table('schedule_categories')->insert(array('name' => 'Quotes-Rejected','text_color' => '#ffffff','bg_color' => '#ff851b'));
        DB::table('schedule_categories')->insert(array('name' => 'Invoice-Draft','text_color' => '#ffffff','bg_color' => '#f39c12'));
        DB::table('schedule_categories')->insert(array('name' => 'Invoice-Sent','text_color' => '#ffffff','bg_color' => '#00c0ef'));
        DB::table('schedule_categories')->insert(array('name' => 'Invoice-Paid-Partial','text_color' => '#ffffff','bg_color' => '#0aa600'));
        DB::table('schedule_categories')->insert(array('name' => 'Invoice-Paid-Full','text_color' => '#ffffff','bg_color' => '#00a65a'));
        DB::table('schedule_categories')->insert(array('name' => 'Invoice-Overdue','text_color' => '#ffffff','bg_color' => '#dd4b39'));
        
        DB::table('schedule_categories')->insert(array('name' => 'Worker Schedule','text_color' => '#000000','bg_color' => '#aaffaa'));
        DB::table('schedule_categories')->insert(array('name' => 'General Appointment','text_color' => '#000000','bg_color' => '#5656ff'));
        DB::table('schedule_categories')->insert(array('name' => 'Client Appointment','text_color' => '#000000','bg_color' => '#d4aaff'));

        //insert scheduler settings
        DB::table('settings')->insert(array('setting_key' => 'addonSchedulerPastdays','setting_value' => '60'));
        DB::table('settings')->insert(array('setting_key' => 'addonSchedulerEventLimit','setting_value' => '5'));
        DB::table('settings')->insert(array('setting_key' => 'addonSchedulerCreateWorkorder','setting_value' => '0'));
        DB::table('settings')->insert(array('setting_key' => 'addonSchedulerVersion','setting_value' => '1.0.2'));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Schema::drop('schedule');
        Schema::drop('schedule_occurrences');
        Schema::drop('schedule_reminder');
        //Schema::drop('temp_table');
        //Schema::drop('schedule_users');
        //Schema::dropIndex('schedule_reminder_schedule_id_foreign');
        //Schema::dropIndex('schedule_resource_schedule_id_foreign');
        //Schema::dropIndex('schedule_occurrence_schedule_id_foreign');
        //Schema::dropForeign('schedule_id');
        Schema::drop('schedule_categories');
        Schema::drop('schedule_resources');

        //delete workorder settings
        DB::table('settings')->where('setting_key', '=', 'addonSchedulerPastdays')->delete();
        DB::table('settings')->where('setting_key', '=', 'addonSchedulerEventLimit')->delete();
        DB::table('settings')->where('setting_key', '=', 'addonSchedulerCreateWorkorder')->delete();
        DB::table('settings')->where('setting_key', '=', 'addonSchedulerVersion')->delete();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
