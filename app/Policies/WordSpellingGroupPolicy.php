<?php

namespace App\Policies;

use App\Models\WordSpellingGroup;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Gate;

class WordSpellingGroupPolicy extends MasterPolicy
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
        //only admins can do this
        return $user->hasRole('admin')
            ? Response::allow()
            : Response::deny('You cannot perform this action.');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\WordSpellingGroup  $wordSpellingGroup
     * @return mixed
     */
    public function view(User $user, WordSpellingGroup $wordSpellingGroup)
    {
        //only admins can do this
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
        //only admins can do this
        return $user->hasRole('admin')
            ? Response::allow()
            : Response::deny('You cannot perform this action.');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\WordSpellingGroup  $wordSpellingGroup
     * @return mixed
     */
    public function update(User $user, WordSpellingGroup $wordSpellingGroup)
    {
        //only admins can do this
        return $user->hasRole('admin')
            ? Response::allow()
            : Response::deny('You cannot perform this action.');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\WordSpellingGroup  $wordSpellingGroup
     * @return mixed
     */
    public function delete(User $user, WordSpellingGroup $wordSpellingGroup)
    {
        //only admins can do this
        return $user->hasRole('admin')
            ? Response::allow()
            : Response::deny('You cannot perform this action.');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\WordSpellingGroup  $wordSpellingGroup
     * @return mixed
     */
    public function restore(User $user, WordSpellingGroup $wordSpellingGroup)
    {
        //only admins can do this
        return $user->hasRole('admin')
            ? Response::allow()
            : Response::deny('You cannot perform this action.');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\WordSpellingGroup  $wordSpellingGroup
     * @return mixed
     */
    public function forceDelete(User $user, WordSpellingGroup $wordSpellingGroup)
    {
        //only admins can do this
        return $user->hasRole('admin')
            ? Response::allow()
            : Response::deny('You cannot perform this action.');
    }
}
