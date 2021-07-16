<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('prenoms');
            $table->enum('sexe',['homme','femme']);
            $table->date('date_naissance');
            $table->string('nationalite');
            $table->string('ethnie')->default(null);
            $table->string('email')->default(null);
            $table->string('lieu_naissance');
            $table->string('residence_ville');
            $table->string('quartier');
            $table->string('contacts_fixe')->default(null);
            $table->string('contacts_cel');
            $table->boolean('assurance')->default(false);
            $table->string('nom_assurance')->default(null);
            $table->string('profession');
            $table->string('formation');
            $table->string('etat_professionnel');
            $table->boolean('instruction')->default(false);
            $table->enum('niveau_instruction',['non scolarise(e)','primaire','secondaire','superieur'])->default('non scolarise(e)');
            $table->enum('status_matrimonial',['celibataire','marie(e)','en couple','divorce(e)','veuf(ve)'])->default('celibataire');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clients');
    }
}
