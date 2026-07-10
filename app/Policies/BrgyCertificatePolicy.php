<?php

namespace App\Policies;

use App\Models\BrgyCertificate;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class BrgyCertificatePolicy
{
    use HandlesAuthorization;
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_brgy_certificates') && $user->hasRole('admin');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, BrgyCertificate $brgyCertificate): bool
    {
        return $user->can('view_brgy_certificates') && $user->hasRole('admin');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_brgy_certificates') && $user->hasRole('admin');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, BrgyCertificate $brgyCertificate): bool
    {
        return $user->can('update_brgy_certificates') && $user->hasRole('admin');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, BrgyCertificate $brgyCertificate): bool
    {
        return $user->can('delete_brgy_certificates') && $user->hasRole('admin');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, BrgyCertificate $brgyCertificate): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, BrgyCertificate $brgyCertificate): bool
    {
        return false;
    }
}
