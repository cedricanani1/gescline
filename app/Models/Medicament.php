<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Medicament extends Model
{
    use HasFactory,SoftDeletes,LogsActivity;
    protected static $logAttributes = ['*'];
    protected $fillable = ['categorie_medicament_id','libelle','dosage','type','prix','quantity'];

    public function categorie()
    {
        return $this->belongsTo('App\Models\CategorieMedicament','categorie_medicament_id');
    }
}
