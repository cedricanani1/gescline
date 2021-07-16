<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdonnanceMedicamentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ordonnance_medicaments', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('ordonnance_id')->references('id')->on('dossier_ordonnances')->onDelete('restrict')->onUpdate('cascade');
            $table->unsignedInteger('examen_id')->references('id')->on('examens')->onDelete('restrict')->onUpdate('cascade');
            $table->string('posologie');
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
        Schema::dropIfExists('ordonnance_medicaments');
    }
}
