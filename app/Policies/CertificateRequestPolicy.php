<?php

namespace App\Policies;

use App\Models\CertificateRequest;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class CertificateRequestPolicy
{
    use HandlesAuthorization;
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        if ($user->hasRole('resident')) {
            return true;
        }
        return $user->can('view_any_certificate_requests') && $user->hasRole('admin');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, CertificateRequest $certificateRequest): bool
    {
        if ($user->hasRole('resident') && $user->can('view_certificate_requests')) {
            return $user->id === $certificateRequest->requested_by;
        }
        return $user->can('view_certificate_requests') && $user->hasRole('admin');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_certificate_requests') && $user->hasAnyRole(['resident', 'admin']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, CertificateRequest $certificateRequest): bool
    {
        return $user->can('update_certificate_requests') && $user->hasRole('admin');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, CertificateRequest $certificateRequest): bool
    {
        if ($user->hasRole('resident') && $user->can('view_certificate_requests')) {
            return $user->id === $certificateRequest->requested_by;
        }
        return $user->can('delete_certificate_requests') && $user->hasRole('admin');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, CertificateRequest $certificateRequest): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, CertificateRequest $certificateRequest): bool
    {
        return false;
    }
}
