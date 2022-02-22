<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class DossierOrdonnance extends Model
{
    use HasFactory,SoftDeletes,LogsActivity;
    protected static $logAttributes = ['*'];
    protected $fillable = ['num','created_by','dossier_id'];

    public function medicaments()
    {
        return $this->belongsToMany('App\Models\Medicament','ordonnance_medicaments','ordonnance_id', 'medicament_id')->withPivot('medicament_name','quantity','posologie','purchased');
    }
}
