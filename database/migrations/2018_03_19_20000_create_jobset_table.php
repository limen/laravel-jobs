<?php
/**
 * Author: LI Mengxiang
 * Email: limengxiang876@gmail.com
 * Date: 2018/3/19
 */
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobsetTable extends \Illuminate\Database\Migrations\Migration
{
    public function up()
    {
        $table = config('jobs.db_tables.jobset_table');
        $connection = config('jobs.database.connection') ?: config('database.default');

        Schema::connection($connection)->create($table, function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 64);
            $table->tinyInteger('status')->default(0);
            $table->dateTimeTz('try_at')->default(null);
            $table->timestamps();
        });
    }

    public function down()
    {
        $connection = config('jobs.db_connection') ?: config('database.default');
        $table = config('jobs.db_tables.jobset_table');
        Schema::connection($connection)->dropIfExists($table);
    }

}
