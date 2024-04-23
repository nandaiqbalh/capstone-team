<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;


class User extends Authenticatable implements JWTSubject

{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'app_user';
    protected $primaryKey = 'user_id';
    public $timestamps = false;

    public static function rules(array $input)
    {
        return [
            'role_id' => 'required',
            'user_name' => 'required|string|max:255',
            'user_password' => 'required|string|min:6',
            'nomor_induk' => 'required|string|unique:app_user,nomor_induk', // Aturan unik untuk nomor_induk
            'angkatan' => 'required|min:4',
            'jenis_kelamin' => 'required',
        ];
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_email',
        'user_password',
        'user_id',
        'user_name',
        'user_email',
        'no_telp',
        'user_',
        'role_id',
        'nomor_induk',
        'user_active',
        'angkatan',
        'jenis_kelamin',
        'created_by',
        'created_date',
        'modified_by',
        'modified_date'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'user_password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Overide laravel password field name
     */
    public function getAuthPassword() {
        return $this->user_password;

    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }
}
