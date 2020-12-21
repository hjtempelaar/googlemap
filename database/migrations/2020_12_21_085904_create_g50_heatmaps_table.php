<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateG50HeatmapsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('g50_heatmaps', function (Blueprint $table) {
             $table->bigIncrements('id');
            $table->string('gemeente')->nullable();
            $table->string('provincie')->nullable();
            $table->string('overall_ranking')->nullable();
            $table->integer('inwoners')->nullable();
            $table->integer('aantal_evenementen')->nullable();
            $table->integer('aantal_bezoeken')->nullable();
            $table->string('evenement_subsidie')->nullable();
            $table->string('subsidie_per_inwoner')->nullable();
            $table->float('longitude',8,5)->nullable();
            $table->float('latitude',8,5)->nullable();
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
        Schema::dropIfExists('g50_heatmaps');
    }
}
