<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Clinique_analyse extends Model
{
    use HasFactory, LogsActivity;
    protected static $logAttributes = ['*'];
    protected $fillable = ['clinique_id','analyses_id','statut'];
}
