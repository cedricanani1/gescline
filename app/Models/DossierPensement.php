<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class DossierPensement extends Model
{
    use HasFactory,SoftDeletes,LogsActivity;
    protected static $logAttributes = ['*'];
    protected $fillable = ['dossier_id','medicament_id','created_by','purchased','assurance'];

    public function medicaments()
    {
        return $this->belongsToMany('App\Models\Medicament','dossier_pensements','dossier_id', 'medicament_id')->withPivot('purchased','assurance');
    }
}
