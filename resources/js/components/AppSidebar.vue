<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { BookOpen, FolderGit2, LayoutGrid, Signature, GitPullRequest } from '@lucide/vue';
import { computed } from 'vue';
import AppLogo from '@/components/AppLogo.vue';
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { dashboard } from '@/routes';
import type { NavItem } from '@/types';
import { useUserRoles } from '@/lib/auth';

import BrgyCertificateController from '@/actions/App/Http/Controllers/Admin/BrgyCertificateController';
import RequestController from '@/actions/App/Http/Controllers/Admin/CertificateRequestController';

import ResidentRequestController from '@/actions/App/Http/Controllers/Resident/CertiRequestController';

const userRoles = useUserRoles();

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: dashboard(),
        icon: LayoutGrid,
    },

    {
        title: 'Brgy Certificates',
        href: BrgyCertificateController.index(),
        icon: Signature,
        roles: ['admin'],
    },
    {
        title: 'Requests',
        href: RequestController.index(),
        icon: GitPullRequest,
        roles: ['admin'],
    },
    {
        title: 'My Requests',
        href: ResidentRequestController.index(),
        icon: GitPullRequest,
        roles: ['resident'],
    },
];

const visibleMainNavItems = computed(() =>
    mainNavItems.filter((item) => {
        if (item.roles && item.roles.length > 0) {
            return item.roles.some((role) => userRoles.value.includes(role));
        }
        return true;
    }),
);

const footerNavItems: NavItem[] = [
    // {
    //     title: 'Repository',
    //     href: 'https://github.com/laravel/vue-starter-kit',
    //     icon: FolderGit2,
    // },
    // {
    //     title: 'Documentation',
    //     href: 'https://laravel.com/docs/starter-kits#vue',
    //     icon: BookOpen,
    // },
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="dashboard()">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="visibleMainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
