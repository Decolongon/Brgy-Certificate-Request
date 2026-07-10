<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import PlaceholderPattern from '@/components/PlaceholderPattern.vue';
import { dashboard } from '@/routes';
import { useIsAdmin, useIsResident } from '@/lib/auth';
import AdminComponent from '@/components/AdminComponent.vue';
import ResidentComponent from '@/components/ResidentComponent.vue'; 

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Dashboard',
                href: dashboard(),
            },
        ],
    },
});
const isAdmin = useIsAdmin();
const isResident = useIsResident();

</script>

<template>
    <Head title="Dashboard" />

    <!-- ─── Admin content ─── -->
    <template v-if="isAdmin">
        <h1>This is admin</h1>
        <AdminComponent />
    </template>

    <!-- ─── Resident content ─── -->
    <template v-if="isResident">
        <ResidentComponent />
    </template>
</template>
