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
    protected $fillable = ['num','created_by'];

    public function medicaments()
    {
        return $this->belongsToMany('App\Models\OrdonnanceMedicament','dossier_diagnostics','dossier_id', 'diagnostic_id')->withPivot('value','description','created_by');
    }
}
