<?php

use Illuminate\Database\Migrations\Migration;

class UpdateSchedulerAutoincrement extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //reset autoincrement on schedule table way out there... kluge to alleviate id overwrite
        DB::unprepared('
            SET @m = (SELECT IFNULL(MAX(id),0) + 1000001 FROM schedule);
            SET @s = CONCAT("ALTER TABLE schedule AUTO_INCREMENT =", @m);
            PREPARE stmt1 FROM @s;
            EXECUTE stmt1;
            DEALLOCATE PREPARE stmt1;
        ');
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
