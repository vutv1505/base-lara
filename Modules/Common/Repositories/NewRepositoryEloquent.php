<?php

namespace Modules\CommonRepositories;

use App\Repositories\MyRepositoryEloquent;
use App\Entities\New;
use App\Presenters\NewPresenter;
use Prettus\Repository\Criteria\RequestCriteria;

/**
 * Class NewRepositoryEloquent.
 *
 * @package namespace Modules\CommonRepositories;
 */
class NewRepositoryEloquent extends MyRepositoryEloquent implements NewRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return New::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
        $this->setPresenter(app(NewPresenter::class));
    }
}
