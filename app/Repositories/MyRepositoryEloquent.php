<?php

namespace App\Repositories;

use App\Repositories\MyRepository;
use Illuminate\Database\Eloquent\Model;

use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class MyRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */

class MyRepositoryEloquent extends BaseRepository implements MyRepository {
	/**
	 * @return string
	 */
	public function model() {
		return '';
	}

	/**
	 * Boot up the repository, pushing criteria
	 */
	public function boot() {
		$this->pushCriteria(app(RequestCriteria::class ));
		$this->setPresenter(app(MyPresenter::class ));
	}

	/**
	 * @return MyRepository|BaseRepository
	 */
	public function with($relations) {
		if ($this->presenter instanceof MyPresenter) {
			$this->presenter->parseIncludes($relations);
		}
		return parent::with($relations);
	}

	public function getById($id, $relations = [], $trashed = false) {
		if ($trashed) {
			$this->model = $this->model->withTrashed();
		}
		return $this->with($relations)->find($id);
	}

	public function getAll($paginate = false) {
		$primaryKey = $this->model->getKeyName();
		$condition  = $this->orderBy($primaryKey, 'DESC');
		if ($paginate) {
			return $condition->paginate();
		}

		return $condition->all();
	}

	public function add($attributes = []) {
		return $this->create($attributes);
	}

	public function addMulti($values = []) {
		$this->model->replace($values);
	}

	public function edit($id, $attributes = []) {
		return $this->update($attributes, $id);
	}

	public function remove($id) {
		return $this->delete($id);
	}

	public function restore($id) {
		$this->model->withTrashed()->find($id)->restore();
	}

	protected function makeDictionary($data, $key, $value) {
		$response = [];
		foreach ($data as $item) {
			$response[$item[$key]] = $item[$value];
		}
		return $response;
	}

	protected function getNameByLanguage() {
		$local = app()->getLocale();
		return 'name_'.$local;
	}

	protected function count(array $where = [], $columns = '*') {
		$this->applyCriteria();
		$this->applyScope();

		if ($where) {
			$this->applyConditions($where);
		}

		$result = $this->model->count($columns);

		$this->resetModel();
		$this->resetScope();

		return $result;
	}

	protected function onlyField($columns = 'id', array $where = []) {
		$this->applyCriteria();
		$this->applyScope();

		if ($where) {
			$this->applyConditions($where);
		}

		$result = $this->model->pluck($columns)->all();

		$this->resetModel();
		$this->resetScope();

		return $result;
	}

	protected function getFirst(array $where = [], $columns = ['*']) {
		$this->applyCriteria();
		$this->applyScope();

		if ($where) {
			$this->applyConditions($where);
		}

		$result = $this->model->first($columns);

		$this->resetModel();
		$this->resetScope();

		if (empty($result)) {
			return [];
		}
		return $this->parserResult($result);
	}

	protected function setLimit($limit) {
		config(['repository.pagination.limit' => $limit]);
	}

}
