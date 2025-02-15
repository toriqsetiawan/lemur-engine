<?php

namespace App\Policies;

use App\Models\Normalization;
use App\Models\User;
use App\Models\Wildcard;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class WildcardPolicy extends MasterPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //only admins can access this model/action
        return $user->hasRole('admin')
            ? Response::allow()
            : Response::deny('You cannot perform this action.');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Wildcard $wildcard
     * @return mixed
     */
    public function view(User $user, Wildcard $wildcard)
    {
        //only admins can access this model/action
        return $user->hasRole('admin')
            ? Response::allow()
            : Response::deny('You cannot perform this action.');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //admins can do this but they should really do this
        //this is an action which happens when the user is talking to the bot
        return $user->hasRole('admin')
            ? Response::allow()
            : Response::deny('You cannot perform this action.');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Wildcard $wildcard
     * @return mixed
     */
    public function update(User $user, Wildcard $wildcard)
    {
        //only admins can access this model/action
        return $user->hasRole('admin')
            ? Response::allow()
            : Response::deny('You cannot perform this action.');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Wildcard $wildcard
     * @return mixed
     */
    public function delete(User $user, Wildcard $wildcard)
    {
        //only admins can access this model/action
        return $user->hasRole('admin')
            ? Response::allow()
            : Response::deny('You cannot perform this action.');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Wildcard $wildcard
     * @return mixed
     */
    public function restore(User $user, Wildcard $wildcard)
    {
        //only admins can access this model/action
        return $user->hasRole('admin')
            ? Response::allow()
            : Response::deny('You cannot perform this action.');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Wildcard $wildcard
     * @return mixed
     */
    public function forceDelete(User $user, Wildcard $wildcard)
    {
        //only admins can access this model/action
        return $user->hasRole('admin')
            ? Response::allow()
            : Response::deny('You cannot perform this action.');
    }
}
