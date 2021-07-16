<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCliniqueDepartementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clinique_departements', function (Blueprint $table) {
            $table->increments('id');
            $table->UNSIGNEDINTEGER('clinique_id')->foreign('clinique_id')->references('id')->on('cliniques')->onDelete('restrict')->onUpdate('cascade');
            $table->UNSIGNEDINTEGER('departement_id')->foreign('departement_id')->references('id')->on('departements')->onDelete('restrict')->onUpdate('cascade');
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
        Schema::dropIfExists('clinique_departements');
    }
}
