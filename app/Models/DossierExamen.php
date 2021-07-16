<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class DossierExamen extends Model
{
    use HasFactory,SoftDeletes,LogsActivity;
    protected static $logAttributes = ['*'];
    protected $fillable = ['dossier_id','examen_id','resultat','created_by','purchased'];
}
