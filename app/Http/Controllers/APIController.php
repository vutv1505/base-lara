<?php

namespace App\Http\Controllers;

use App\User;
use Carbon\Carbon;
use Dingo\Api\Http\Response;
use Dingo\Api\Routing\Helpers;
use Illuminate\Routing\Controller;
use Modules\API\Common\Repositories\UserRepository;

class APIController extends Controller
{
    //
    use Helpers;

    /**
     * @var array|\Illuminate\Http\Request|string
     */
    protected $request;
    /**
     * @var \Illuminate\Contracts\Foundation\Application|\Illuminate\Log\LogManager|mixed
     */
    protected $log;

    public function __construct()
    {
        $this->request = request();
        $this->log = app('log');
    }
    /**
     * Created by VuTV
     * @return mixed
     */
    protected function authUser()
    {
        $user = $this->user;
        return $user->toArray();
    }
    /**
     * Created by VuTV
     * @return mixed
     */
    protected function authId()
    {
        if ($this->user instanceof User) {
            return $this->user->id;
        }
        return 0;
    }
    protected function responseData($data = [])
    {
        if ($this->request->get('debug') == 1 && config('app.env') == 'local') {
            echo '<pre>' . print_r($data, true) . '</pre>';
            return 'ok';
        }
        return new Response($data, Response::HTTP_OK);
    }
    protected function responseError($message, $code = 404)
    {
        app('log')->debug('responseError: ' . $message);
        $this->response->error($message, $code);
    }
    protected function badRequest($message)
    {
        $this->response->errorBadRequest($message);
    }
    protected function submitFormError($field, $message)
    {
        $error = [$field => [$message]];
        return new Response([
            'errors' => $error,
            'status_code' => Response::HTTP_UNPROCESSABLE_ENTITY
        ], Response::HTTP_UNPROCESSABLE_ENTITY);
    }
    protected function makeSessionId($user)
    {
        app('log')->debug(var_export($user, true));
        $token = $user->session_id;
        $userId = $user->id;
        $expired = Carbon::parse($user->session_expired);
        $now = Carbon::now();
        if (empty($user->session_expired) | $expired->diffInSeconds($now, false) > 0) {
            $token = \Hashids::encode($userId) . '_' . md5(uniqid());
        }
        return $token;
    }
    public function responseUserSocial($user)
    {
        $response = [];
        $sessionId = $this->makeSessionId($user);
        app(UserRepository::class)->updateSessionId($sessionId, $user->id);
        $response['session_id'] = $sessionId;
        $response['role'] = config('constant.user_level.applicant');
        $response['user_id'] = $user->id;
        return $response;
    }
}
