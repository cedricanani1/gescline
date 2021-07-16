<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLieuDeTravailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lieu_de_travails', function (Blueprint $table) {
            $table->increments('id');
            $table->UNSIGNEDINTEGER('clinique_id')->foreign('clinique_id')->references('id')->on('cliniques')->onDelete('restrict')->onUpdate('cascade');
            $table->UNSIGNEDINTEGER('departement_id')->foreign('departement_id')->references('id')->on('departements')->onDelete('restrict')->onUpdate('cascade');
            $table->UNSIGNEDINTEGER('service_id')->foreign('service_id')->references('id')->on('services')->onDelete('restrict')->onUpdate('cascade');
            $table->UNSIGNEDINTEGER('user_id')->foreign('user_id')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
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
        Schema::dropIfExists('lieu_de_travails');
    }
}
