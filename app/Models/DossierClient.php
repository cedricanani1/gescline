<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DossierClient extends Model
{
    use HasFactory,SoftDeletes,LogsActivity;
    protected static $logAttributes = ['*'];
    protected $fillable = ['client_id','num','objet','created_by'];
    public function constantes()
    {
        return $this->belongsToMany('App\Models\Constante','dossier_constantes', 'dossier_id', 'constante_id')->withPivot('value','created_by');
    }
    public function pensements()
    {
        return $this->belongsToMany('App\Models\Medicament','dossier_pensements', 'dossier_id', 'medicament_id')->withPivot('purchased','created_by');
    }
    public function examens()
    {
        return $this->belongsToMany('App\Models\Examen','dossier_examens', 'dossier_id', 'examen_id')->withPivot('purchased','created_by','resultat');
    }
    public function traitements()
    {
        return $this->belongsToMany('App\Models\Traitement','dossier_traitements','dossier_id', 'traitement_id')->withPivot('dose','voie','heure','created_by');
    }
    public function assurance()
    {
        return $this->belongsToMany('App\Models\Assurance','dossier_assurances','dossier_id', 'assurance_id')->withPivot('numero_bon','matricule','acte','created_by');
    }
    public function diagnostics()
    {
        return $this->belongsToMany('App\Models\Diagnostic','dossier_diagnostics','dossier_id', 'diagnostic_id')->withPivot('value','description','created_by');
    }
    public function ordonnances()
    {
        return $this->hasMany('App\Models\DossierOrdonnance','dossier_id');
    }
    public function rendezVous()
    {
        return $this->hasOne('App\Models\DossierRendezVous','dossier_id');
    }
    public function fileAttente()
    {
        return $this->hasOne('App\Models\FileAttente','dossier_id');
    }
}
