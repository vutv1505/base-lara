<?php

namespace Modules\CommonRepositories;

use App\Repositories\MyRepositoryEloquent;
use App\Entities\New1;
use App\Presenters\New1Presenter;
use Prettus\Repository\Criteria\RequestCriteria;

/**
 * Class New1RepositoryEloquent.
 *
 * @package namespace Modules\CommonRepositories;
 */
class New1RepositoryEloquent extends MyRepositoryEloquent implements New1Repository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return New1::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
        $this->setPresenter(app(New1Presenter::class));
    }
}
