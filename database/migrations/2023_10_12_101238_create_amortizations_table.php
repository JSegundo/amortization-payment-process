<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAmortizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('amortizations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained();
            $table->date('schedule_date');
            $table->string('state');
            $table->float('amount');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
          Schema::table('amortizations', function (Blueprint $table) {
        $table->dropForeign(['project_id']);
    });

    Schema::dropIfExists('amortizations');
    }
}
