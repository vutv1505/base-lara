<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class User
 * @package App\Entities
 */
class User extends MyEntity
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'email_verified_at',
    ];
}
