<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use jdavidbakr\ReplaceableModel\ReplaceableModel;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class MyEntity.
 * @author VuTV
 *
 * @package namespace App\Entities;
 */

class MyEntity extends Model implements Transformable {
	use TransformableTrait;
	use ReplaceableModel;
	use SoftDeletes;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [];

	/**
	 * hash id to response: only work API
	 */
	public $hashIds = [];

	public $jsonColumns = [];

}
