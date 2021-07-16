<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCliniqueDepartementServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clinique_departement_services', function (Blueprint $table) {
            $table->id();
            $table->UNSIGNEDINTEGER('clinique_id')->foreign('clinique_id')->references('id')->on('cliniques')->onDelete('restrict')->onUpdate('cascade');
            $table->UNSIGNEDINTEGER('departement_id')->foreign('departement_id')->references('id')->on('departements')->onDelete('restrict')->onUpdate('cascade');
            $table->UNSIGNEDINTEGER('service_id')->foreign('service_id')->references('id')->on('services')->onDelete('restrict')->onUpdate('cascade');
             $table->enum('statut',['actif','inactif'])->default('actif');
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
        Schema::dropIfExists('clinique_departement_services');
    }
}
