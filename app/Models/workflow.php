<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class workflow extends Model
{
    use HasFactory, LogsActivity;
    protected static $logAttributes = ['*'];
    protected $fillable = ['source_service_id','destination_service_id','clinique_id','commentaire'];
}
