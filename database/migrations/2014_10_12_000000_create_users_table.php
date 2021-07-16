<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('prenoms');
            $table->string('nationalite');
            $table->string('telephone');
            $table->date('date_naissance');
            $table->string('adresse_domicile');
            $table->enum('situation_matrimoniale',['celibataire','mariee','veuf','divorcee','en couple']);
            $table->enum('genre',['homme','femme']);
            $table->string('email')->unique();
            $table->timestamp('email_verifiee_a')->nullable();
            $table->string('password');
            $table->enum('role',['admin','utilisateur']);
            $table->enum('statut',['actif','inactif'])->default('actif');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
