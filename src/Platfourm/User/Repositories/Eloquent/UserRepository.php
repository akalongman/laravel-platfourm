<?php

namespace Longman\Platfourm\User\Repositories\Eloquent;

use GuzzleHttp\Client;
use Illuminate\Contracts\Cache\Repository as Cache;
use Longman\Platfourm\Contracts\Auth\AuthUserService;
use Longman\Platfourm\Contracts\Repository\Repository;
use Longman\Platfourm\Contracts\Repository\RepositoryCriteria;
use Longman\Platfourm\Repository\Eloquent\BaseRepository;
use Longman\Platfourm\User\Models\Eloquent\User;

class UserRepository extends BaseRepository implements Repository, RepositoryCriteria
{

    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
        if (class_exists(\App\Models\User::class)) {
            return \App\Models\User::class;
        }

        return User::class;
    }

    /**
     * Attach role to user.
     *
     * @param type $roleName
     *
     * @return type
     */
    public function updateAvatar(Client $httpClient, Cache $cache, AuthUserService $authUserService)
    {
        $user_id   = $authUserService->user()->id;
        $email     = $authUserService->user()->email;
        $hash      = md5(strtolower(trim($email)));
        $cache_key = 'gravatar_' . $hash;

        if ($cache->has($cache_key)) {
            return true;
        }

        $cache->put($cache_key, 1, config('cms.user.avatar.gravatar_cache_ttl', 1440));

        $grav_url = 'https://www.gravatar.com/avatar/' . $hash . '&s=512&r=x';

        $image_name = $hash . '.jpg';
        $path       = public_path(config('cms.user.avatar.path', 'cache/avatar'));
        try {
            $httpClient->request('GET', $grav_url, ['sink' => $path . '/' . $image_name]);

            $this->model->findOrFail($user_id)->update(['avatar' => $image_name]);
            $authUserService->user()->avatar = $image_name;
        } catch (Exception $e) {
            return false;
        }

        return true;
    }

    /**
     * Attach role to user.
     *
     * @param type $roleName
     *
     * @return type
     */
    public function attachRole($roleName)
    {
        return $this->model->attachRole($roleName);
    }

    /**
     * Attach permission to user.
     *
     * @param string $permissionName
     * @param array  $options
     *
     * @return type
     */
    public function attachPermission($permissionName, array $options = [])
    {
        return $this->model->attachPermission($permissionName, $options);
    }

    /**
     * Activate user with the given id.
     *
     * @param type $id
     *
     * @return type
     */
    public function activate($id)
    {
        $user = $this->model->whereId($id)->whereStatus('New')->first();

        if (is_null($user)) {
            return false;
        }

        if ($user->update(['status' => 'Active'])) {
            return true;
        }

        return false;
    }

}
