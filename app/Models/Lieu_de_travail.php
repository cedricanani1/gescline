<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Lieu_de_travail extends Model
{
    use HasFactory, LogsActivity;
    protected static $logAttributes = ['*'];

    protected $fillable = ['clinique_id','departement_id','service_id','user_id','statut'];
}
