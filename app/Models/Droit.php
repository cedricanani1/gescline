<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Droit extends Model
{
    use HasFactory,LogsActivity;
    protected static $logAttributes = ['*'];
    protected $fillable = ['user_id', 'module_id', 'create','write', 'read', 'update', 'delete', 'import', 'export', 'transfert', 'assigner'];
}
