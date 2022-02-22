<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
class FileAttente extends Model
{
    use HasFactory,LogsActivity;
    protected static $logAttributes = ['*'];
    protected $fillable = ['dossier_id','num_ordre','service_id','status'];

    public function dossier()
    {
        return $this->belongsTo('App\Models\DossierClient')->orderBy('created_at','ASC');
    }
    public function facture()
    {
        return $this->hasMany('App\Models\Facture','dossier_id');
    }

}
