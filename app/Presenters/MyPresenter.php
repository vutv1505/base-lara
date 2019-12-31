<?php

namespace App\Presenters;

use App\Transformers\MyTransformer;
use Illuminate\Support\Str;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class MyPresenter.
 *
 * @package namespace App\Presenters;
 */

class MyPresenter extends FractalPresenter {
	public function parseIncludes($includes = array()) {
		if (!empty($includes)) {
			$relations = [];
			foreach ($includes as $key => $value) {
				if ($value instanceof \Closure) {
					$relations[] = Str::snake($key);
				} else {
					$relations[] = Str::snake($value);
				}
			}
			$includes = $relations;
		}

		$this->fractal->parseIncludes($includes);
	}

	/**
	 * Transformer
	 *
	 * @return \League\Fractal\TransformerAbstract
	 */
	public function getTransformer() {
		return new MyTransformer();
	}
}
