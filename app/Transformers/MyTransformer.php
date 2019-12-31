<?php

namespace App\Transformers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use League\Fractal\TransformerAbstract;

/**
 * Class MyTransformer.
 *
 * @package namespace App\Transformers;
 */

class MyTransformer extends TransformerAbstract {
	/**
	 * Transform the data.
	 *
	 * @param data
	 *
	 * @return array
	 */
	protected function transformData($data) {
		if (empty($data)) {
			return null;
		}

		if (!($data instanceof Model)) {
			return [];
		}

		$className   = get_class($data);
		$arr         = explode('\\', $className);
		$className   = array_pop($arr);
		$transformer = 'App\Transformers\\'.$className.'Transformer';
		return (new $transformer())->transform($data);
	}

	private function transformerRelation($model, &$attribute) {
		if (!($model instanceof Model)) {
			return [];
		}
		$relations = $model->getRelations();
		if (!empty($relations)) {
			foreach ($relations as $key => $relation) {
				$data = [];
				if (!empty($relation)) {
					if ($relation instanceof Collection) {
						$data = [];
						foreach ($relation as $item) {
							$data[] = $this->transformData($item);
						}
					} else {
						$data = $this->transformData($relation);
					}
				}
				$attribute[Str::snake($key)] = $data;
			}
		}
	}

	/**
	 * @param Model $model
	 * @return array
	 */
	public function transform(Model $model) {
		$attribute = $model->toArray();
		if (!empty($model->hashIds)) {
			$this->hashId($model->hashIds, $attribute);
		}

		if (!empty($model->jsonColumns)) {
			$this->jsonDecodeData($model->jsonColumns, $attribute);
		}

		$this->transformerRelation($model, $attribute);

		return $attribute;
	}

	private function jsonDecodeData($columns, &$attribute) {
		$keys = array_keys($attribute);
		foreach ($columns as $column) {
			if (!empty($attribute[$column])) {
				$attribute[$column] = json_decode($attribute[$column], true);
			} elseif (!isset($attribute[$column]) && in_array($column, $keys)) {
				$attribute[$column] = [];
			}
		}
	}

	private function hashId($columns, &$attribute) {
		foreach ($columns as $column) {
			if (isset($attribute[$column])) {
				if ($attribute[$column] == 0) {
					$attribute[$column] = '';
				} else {
					$attribute[$column.'_hash'] = \Hashids::encode($attribute[$column]);
				}
			}
		}
	}
}
