<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHeatmapsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('heatmaps', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('bereik')->nullable();
            $table->integer('totaal_direct_bereik')->nullable();
            $table->integer('evenement_id')->nullable();
            $table->string('evenement')->nullable();
            $table->string('subcategorienaam')->nullable();
            $table->string('vormcategorienaam')->nullable();
            $table->string('nen_plaats')->nullable();
            $table->integer('eveditie')->nullable();
            $table->date('startdatum')->nullable();
            $table->date('einddatum')->nullable();
            $table->string('datumnotitie')->nullable();
            $table->string('editiewijziging')->nullable();
            $table->integer('aantal_dagen')->nullable();
            $table->string('locatienaam')->nullable();
            $table->integer('jaar')->nullable();
            $table->integer('deelnemers')->nullable();
            $table->integer('corop')->nullable();
            $table->string('gemeente')->nullable();
            $table->integer('eg_id')->nullable();
            $table->integer('tblstoreegvormcategorie_numerik')->nullable();
            $table->integer('tblstoreegsubinhoudscategorie_numerik')->nullable();
            $table->string('mra')->nullable();
            $table->string('jobcode')->nullable();
            $table->integer('binnenbuiteneditie')->nullable();
            $table->integer('entree')->nullable();
            $table->string('categorienaam')->nullable();
            $table->integer('subcategorieid')->nullable();
            $table->integer('landcode')->nullable();
            $table->string('provincie')->nullable();
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
        Schema::dropIfExists('heatmaps');
    }
}
