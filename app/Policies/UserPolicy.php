<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    function update(User $user) {
        return $user->isAdministrator();
    }

    function create(User $user) {
        return $this->update($user);
    }

    function delete(User $user) {
        return $this->update($user);
    }

    function view(User $user) {
        return $this->update($user);
    }
}
