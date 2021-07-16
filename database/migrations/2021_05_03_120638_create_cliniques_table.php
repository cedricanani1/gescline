<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCliniquesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cliniques', function (Blueprint $table) {
            $table->increments('id');
            $table->string('numero_identifiant')->unique();
            $table->string('nom');
            $table->string('telephone')->unique();
            $table->string('email')->nullable();
            $table->string('telephone_urgence')->unique();
            $table->string('adresse_physique');
            $table->string('adresse_postale')->nullable();
            $table->string('fax')->nullable();
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
        Schema::dropIfExists('cliniques');
    }
}
