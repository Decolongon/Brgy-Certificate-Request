import { usePage } from '@inertiajs/vue3';
import { computed, type ComputedRef } from 'vue';

/**
 * Get the current user's role names shared via HandleInertiaRequests.
 */
export function useUserRoles(): ComputedRef<string[]> {
    const page = usePage();
    return computed(() => (page.props.userRoles as string[]) ?? []);
}

/**
 * Check if the current user has a specific role.
 */
export function useHasRole(role: string): ComputedRef<boolean> {
    const roles = useUserRoles();
    return computed(() => roles.value.includes(role));
}

/**
 * Check if the current user has any of the given roles.
 */
export function useHasAnyRole(rolesToCheck: string[]): ComputedRef<boolean> {
    const roles = useUserRoles();
    return computed(() => rolesToCheck.some((role) => roles.value.includes(role)));
}

/**
 * Shortcut: check if the current user is an admin.
 */
export function useIsAdmin(): ComputedRef<boolean> {
    return useHasRole('admin');
}

/**
 * Shortcut: check if the current user is a resident.
 */
export function useIsResident(): ComputedRef<boolean> {
    return useHasRole('resident');
}

/**
 * Check if the current user has a specific permission (via page.props.auth.user.permissions).
 */
export function useCan(permission: string): ComputedRef<boolean> {
    const page = usePage();
    return computed(() => {
        const permissions = (page.props.auth?.user as Record<string, unknown> | undefined)
            ?.permissions as string[] | undefined;
        return Array.isArray(permissions) && permissions.includes(permission);
    });
}

// ─── Imperative (non-composable) helpers for use in template expressions ───

/**
 * Get the raw user roles array inside a template or plain JS context.
 * Usage: `v-if="hasRole($page.props.userRoles, 'admin')"`
 */
export function hasRole(userRoles: string[] | null | undefined, role: string): boolean {
    return Array.isArray(userRoles) && userRoles.includes(role);
}

/**
 * Check if user has any of the given roles (plain function).
 */
export function hasAnyRole(userRoles: string[] | null | undefined, rolesToCheck: string[]): boolean {
    return Array.isArray(userRoles) && rolesToCheck.some((role) => userRoles.includes(role));
}
