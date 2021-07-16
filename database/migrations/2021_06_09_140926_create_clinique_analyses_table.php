<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCliniqueAnalysesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clinique_analyses', function (Blueprint $table) {
            $table->id();
            $table->UNSIGNEDINTEGER('clinique_id')->foreign('clinique_id')->references('id')->on('cliniques')->onDelete('restrict')->onUpdate('cascade');
            $table->UNSIGNEDINTEGER('analyses_id')->foreign('analyses_id')->references('id')->on('analyses')->onDelete('restrict')->onUpdate('cascade');
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
        Schema::dropIfExists('clinique_analyses');
    }
}
