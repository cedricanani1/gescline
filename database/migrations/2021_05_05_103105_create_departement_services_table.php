<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDepartementServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('departement_services', function (Blueprint $table) {
            $table->increments('id');
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
        Schema::dropIfExists('departement_services');
    }
}
