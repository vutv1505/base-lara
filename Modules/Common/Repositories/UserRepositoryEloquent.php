<?php

namespace Modules\CommonRepositories;

use App\Repositories\MyRepositoryEloquent;
use App\Entities\User;
use App\Presenters\UserPresenter;
use Prettus\Repository\Criteria\RequestCriteria;

/**
 * Class UserRepositoryEloquent.
 *
 * @package namespace Modules\CommonRepositories;
 */
class UserRepositoryEloquent extends MyRepositoryEloquent implements UserRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return User::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
        $this->setPresenter(app(UserPresenter::class));
    }
}
