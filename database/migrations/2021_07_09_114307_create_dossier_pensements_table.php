<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDossierPensementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dossier_pensements', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('dossier_id')->references('id')->on('dossier_clients')->onDelete('restrict')->onUpdate('cascade');
            $table->unsignedInteger('medicament_id')->references('id')->on('medicaments')->onDelete('restrict')->onUpdate('cascade');
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
        Schema::dropIfExists('dossier_pensements');
    }
}
