<?php

namespace App\Models;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, LogsActivity;

    protected static $logAttributes = ['*'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['nom','prenoms','nationalite','telephone','date_naissance','adresse_domicile','situation_matrimoniale','genre','email','password','role','statut'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function clinique()
    {
        return $this->belongsTo(Cliniques::class);
    }

    public function permissions()
    {
        return $this->belongsToMany('App\Models\Module','droits', 'user_id', 'module_id')->withPivot('create','write', 'read','update','delete','import','export','transfert','assigner');
    }

    public function profile()
    {
        return $this->belongsToMany(Profile::class,ProfileUser::class,'user_id','profile_id')->withPivot('statut','id');
    }

}
