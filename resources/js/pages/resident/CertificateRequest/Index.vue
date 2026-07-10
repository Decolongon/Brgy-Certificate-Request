<script setup lang="ts">
import { computed, ref } from 'vue';
import { Form, Head, usePage } from '@inertiajs/vue3';
import CertiRequestController from '@/actions/App/Http/Controllers/Resident/CertiRequestController';
import Heading from '@/components/Heading.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Combobox } from '@/components/ui/combobox';

interface BrgyCertificate {
    id: number;
    cert_name: string;
    slug: string;
    cert_description: string | null;
}

interface CertificateRequest {
    id: number;
    brgy_cert_id: number;
    requested_by: number;
    status: 'new' | 'pending' | 'processing' | 'ready_to_pick_up';
    pick_up_at: string | null;
    brgy_cert_request: BrgyCertificate;
    request_by: {
        id: number;
        name: string;
        email: string;
    };
}

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'My Certificate Requests',
                href: CertiRequestController.index(),
            },
        ],
    },
});

const props = defineProps<{
    certificateRequests: CertificateRequest[];
    brgyCertificates: BrgyCertificate[];
}>();

const page = usePage();
const authUser = computed(() => page.props.auth.user);

const certificateItems = computed(() =>
    props.brgyCertificates.map((cert) => ({
        value: cert.id,
        label: cert.cert_name,
    })),
);

const createCertId = ref<number | null>(null);

const statusVariant: Record<string, 'secondary' | 'default' | 'outline' | 'destructive'> = {
    new: 'secondary',
    pending: 'default',
    processing: 'default',
    ready_to_pick_up: 'outline',
};

const statusLabels: Record<string, string> = {
    new: 'New',
    pending: 'Pending',
    processing: 'Processing',
    ready_to_pick_up: 'Ready to Pick Up',
};
</script>

<template>

    <Head title="My Certificate Requests" />

    <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
        <div class="flex items-center justify-between">
            <Heading title="My Certificate Requests" description="Manage your barangay certificate requests" />

            <Dialog>
                <DialogTrigger as-child>
                    <Button>New Request</Button>
                </DialogTrigger>

                <DialogContent>
                    <DialogHeader class="space-y-3">
                        <DialogTitle>New Certificate Request</DialogTitle>
                        <DialogDescription>
                            Select the type of barangay certificate you need.
                        </DialogDescription>
                    </DialogHeader>

                    <Form v-bind="CertiRequestController.store.form()" v-slot="{ errors, processing }" reset-on-success
                        :on-success="() => { createCertId = null; }">
                        <div class="grid gap-4 py-2">
                            <div class="grid gap-2">
                                <Label for="brgy_cert_id">Certificate Type</Label>
                                <Combobox id="brgy_cert_id" name="brgy_cert_id" :items="certificateItems"
                                    placeholder="Select a certificate..." :model-value="createCertId"
                                    @update:model-value="(v: unknown) => (createCertId = v as number | null)" />
                                <input type="hidden" name="brgy_cert_id" :value="createCertId ?? ''" />
                                <p v-if="errors.brgy_cert_id" class="text-sm text-destructive">
                                    {{ errors.brgy_cert_id }}
                                </p>
                            </div>

                            <div class="grid gap-2">
                                <Label for="pick_up_at">Preferred Pick Up Date (optional)</Label>
                                <Input id="pick_up_at" name="pick_up_at" type="date" />
                                <p v-if="errors.pick_up_at" class="text-sm text-destructive">
                                    {{ errors.pick_up_at }}
                                </p>
                            </div>
                        </div>

                        <DialogFooter class="gap-2">
                            <DialogClose as-child>
                                <Button variant="secondary" type="button">Cancel</Button>
                            </DialogClose>
                            <Button type="submit" :disabled="processing">
                                Submit Request
                            </Button>
                        </DialogFooter>
                    </Form>
                </DialogContent>
            </Dialog>
        </div>

        <div class="rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-sidebar-border/70 text-left text-sm text-muted-foreground">
                        <th class="px-4 py-3 font-medium">Certificate</th>
                        <th class="px-4 py-3 font-medium">Status</th>
                        <th class="px-4 py-3 font-medium">Pick Up Date</th>
                        <th class="px-4 py-3 font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="request in certificateRequests" :key="request.id"
                        class="border-b border-sidebar-border/70 last:border-b-0">
                        <td class="px-4 py-3 text-sm">
                            {{ request.brgy_cert_request?.cert_name || 'Unknown' }}
                        </td>
                        <td class="px-4 py-3">
                            <Badge :variant="statusVariant[request.status] || 'secondary'">
                                {{ statusLabels[request.status] || request.status }}
                            </Badge>
                        </td>
                        <td class="px-4 py-3 text-sm text-muted-foreground">
                            {{ request.pick_up_at ? request.pick_up_at.split('T')[0] : 'N/A' }}
                        </td>
                        <td class="px-4 py-3">
                            <Dialog>
                                <DialogTrigger as-child>
                                    <Button variant="destructive" size="sm">
                                        Delete
                                    </Button>
                                </DialogTrigger>

                                <DialogContent>
                                    <DialogHeader class="space-y-3">
                                        <DialogTitle>Delete Certificate Request</DialogTitle>
                                        <DialogDescription>
                                            Are you sure you want to delete this certificate request for
                                            <strong>&ldquo;{{ request.brgy_cert_request?.cert_name || 'Unknown'
                                                }}&rdquo;</strong>?
                                            This action cannot be undone.
                                        </DialogDescription>
                                    </DialogHeader>

                                    <Form
                                        v-bind="CertiRequestController.destroy.form({ certificate_request: request.id })"
                                        v-slot="{ processing }">
                                        <DialogFooter class="gap-2">
                                            <DialogClose as-child>
                                                <Button variant="secondary">Cancel</Button>
                                            </DialogClose>

                                            <Button type="submit" variant="destructive" :disabled="processing">
                                                Delete
                                            </Button>
                                        </DialogFooter>
                                    </Form>
                                </DialogContent>
                            </Dialog>
                        </td>
                    </tr>

                    <tr v-if="certificateRequests.length === 0">
                        <td colspan="4" class="px-4 py-8 text-center text-sm text-muted-foreground">
                            No certificate requests yet. Click "New Request" to create one.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
