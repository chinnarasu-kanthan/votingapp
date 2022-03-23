<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDistrictTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('district', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('state_id');
            $table->foreign('state_id')->references('id')->on('states');
            $table->string('district_name');
            $table->enum('status', ['Y', 'N']);
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
        Schema::dropIfExists('district');
    }
}
