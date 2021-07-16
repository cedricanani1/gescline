<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Clinique extends Model
{
    use HasFactory, LogsActivity;
    protected static $logAttributes = ['*'];
    // add email
    protected $fillable = ['numero_identifiant','nom','telephone','email','telephone_urgence','adresse_physique','adresse_postale','fax','statut'];

        //ManyToOne
        public function users(){
            return $this->belongsTo(Users::class);
        }

}
