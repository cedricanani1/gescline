<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Assurance extends Model
{
    use HasFactory, LogsActivity;
    protected static $logAttributes = ['*'];
    protected $fillable = ['nom','entreprise','statut','pourcentage'];

    public function dossiers(){
        return $this->belongsToMany('App\Models\DossierClient','dossier_assurances', 'assurance_id','dossier_id')->withPivot('numero_bon','matricule','acte','created_by','pourcentage');
    }
}
