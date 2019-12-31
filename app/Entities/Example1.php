<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Example1
 * @package App\Entities
 */
class Example1 extends MyEntity
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = '';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];
}
