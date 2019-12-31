<?php

namespace App\Repositories;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface MyRepository.
 *
 * @package namespace App\Repositories;
 */
interface MyRepository extends RepositoryInterface {
	/**
	 * @param $id
	 * @return mixed
	 */
	public
function getById($id, $relations = [], $trashed = false);

	/**
	 * @param bool $paginate
	 * @return mixed
	 */
	public function getAll($paginate = false);

	/**
	 * Description: add new record
	 * @param array $attributes
	 * @return mixed
	 */
	public function add($attributes = []);

	/**
	 * @param array $values
	 * @return mixed
	 */
	public function addMulti($values = []);

	/**
	 * Description: update record with id
	 * @param $id
	 * @param array $attributes
	 * @return mixed
	 */
	public function edit($id, $attributes = []);

	/**
	 * Description: remove record with id
	 * @param $id
	 * @return mixed
	 */
	public function remove($id);

	/**
	 * Description:
	 * @param $id
	 * @return mixed
	 */
	public function restore($id);
}
