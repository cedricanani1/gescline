<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFileAttentesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('file_attentes', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('dossier_id')->references('id')->on('dossier_clients')->onDelete('restrict')->onUpdate('cascade');
            $table->string('num_ordre');
            $table->unsignedInteger('profile_id')->references('id')->on('profiles')->onDelete('restrict')->onUpdate('cascade');
            $table->enum('status',['en attente','en cours','termine'])->default('en attente');
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
        Schema::dropIfExists('file_attentes');
    }
}
