<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDossierTraitementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dossier_traitements', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('dossier_id')->references('id')->on('dossier_clients')->onDelete('restrict')->onUpdate('cascade');
            $table->unsignedInteger('traitement_id')->references('id')->on('traitements')->onDelete('restrict')->onUpdate('cascade');
            $table->string('dose');
            $table->string('voie');
            $table->string('heure');
            $table->integer('created_by');
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
        Schema::dropIfExists('dossier_traitements');
    }
}
