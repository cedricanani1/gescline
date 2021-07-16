<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Clinique_departement extends Model
{
    use HasFactory, LogsActivity;
    protected static $logAttributes = ['*'];

    protected $fillable = ['departement_id','clinique_id','statut'];
}
