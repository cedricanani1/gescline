<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
class Module extends Model
{
    use HasFactory,LogsActivity;
    protected static $logAttributes = ['*'];
    protected $fillable = ['libelle'];
}
