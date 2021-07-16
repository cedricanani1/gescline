<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDossierClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dossier_clients', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('client_id')->references('id')->on('clients')->onDelete('restrict')->onUpdate('cascade');
            $table->string('num');
            $table->string('objet');
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
        Schema::dropIfExists('dossier_clients');
    }
}
