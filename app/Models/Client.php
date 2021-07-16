<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Client extends Model
{
    use HasFactory,SoftDeletes,LogsActivity;
    protected static $logAttributes = ['*'];
    protected $fillable = ['nom','prenoms','sexe','date_naissance','nationalite','ethnie','lieu_naissance','residence_ville','quartier','email','contacts_fixe','matricule',
                            'contacts_cel','assurance','nom_assurance','profession','formation','etat_professionnel','instruction','niveau_instruction','status_matrimonial'];

    public function dossiers()
    {
        return $this->hasMany('App\Models\DossierClient','client_id')->orderBy('created_at','desc');
    }
}
