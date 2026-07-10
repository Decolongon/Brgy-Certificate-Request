<script setup lang="ts">
import { computed, ref } from 'vue';
import { Form, Head, usePage } from '@inertiajs/vue3';
import RequestController from '@/actions/App/Http/Controllers/Admin/CertificateRequestController';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
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

interface RequestUser {
    id: number;
    name: string;
    email: string;
}

interface CertificateRequest {
    id: number;
    brgy_cert_id: number;
    requested_by: number;
    status: 'new' | 'pending' | 'processing' | 'ready_to_pick_up';
    pick_up_at: string | null;
    brgy_cert_request: BrgyCertificate;
    request_by: RequestUser;
}

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Brgy Certificate Requests',
                href: RequestController.index(),
            },
        ],
    },
});

const props = defineProps<{
    certificateRequests: CertificateRequest[];
    certificates: BrgyCertificate[];
    brgyResidents: { id: number; name: string }[];
}>();

const page = usePage();
const authUser = computed(() => page.props.auth.user);
const isResident = computed(() => {
    const user = authUser.value;
    if (!user) return false;
    return (user as Record<string, unknown>).role === 'resident'
        || (Array.isArray((user as Record<string, unknown>).roles) && (user as Record<string, unknown>).roles.some(
            (r: unknown) => typeof r === 'object' && r !== null && (r as Record<string, unknown>).name === 'resident'
        ));
});

const certificateItems = computed(() =>
    props.certificates.map((cert) => ({
        value: cert.id,
        label: cert.cert_name,
    })),
);

const residentItems = computed(() =>
    props.brgyResidents.map((resident) => ({
        value: resident.id,
        label: resident.name,
    })),
);

const createCertId = ref<number | null>(null);
const createResidentId = ref<number | null>(isResident.value && authUser.value ? authUser.value.id : null);
const editCertIds = ref<Record<number, number | null>>({});
const editResidentIds = ref<Record<number, number | null>>({});

function getEditCertId(requestId: number): number | null {
    return editCertIds.value[requestId] ?? null;
}

function setEditCertId(requestId: number, value: number | string | null): void {
    editCertIds.value[requestId] = value as number | null;
}

function getEditResidentId(requestId: number): number | null {
    return editResidentIds.value[requestId] ?? null;
}

function setEditResidentId(requestId: number, value: number | string | null): void {
    editResidentIds.value[requestId] = value as number | null;
}

const statusVariant: Record<string, 'secondary' | 'default' | 'outline' | 'destructive'> = {
    new: 'secondary',
    pending: 'default',
    processing: 'outline',
    ready_to_pick_up: 'destructive',
};

const statusLabel: Record<string, string> = {
    new: 'New',
    pending: 'Pending',
    processing: 'Processing',
    ready_to_pick_up: 'Ready to Pick Up',
};

const formatDate = (dateString: string | null): string => {
    if (!dateString) return '\u2014';
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
    });
};
</script>

<template>
    <Head title="Brgy Certificate Requests" />

    <div class="flex flex-col gap-6">
        <div class="flex items-center justify-between">
            <Heading title="Brgy Certificate Requests" />

            <Dialog>
                <DialogTrigger as-child>
                    <Button>Create Request</Button>
                </DialogTrigger>

                <DialogContent>
                    <Form
                        v-bind="RequestController.store.form()"
                        resetOnSuccess
                        class="space-y-6"
                        v-slot="{ errors, processing, reset, clearErrors }"
                        @success="
                            () => {
                                createCertId = null;
                                createResidentId = isResident && authUser ? authUser.id : null;
                            }
                        "
                    >
                        <DialogHeader class="space-y-3">
                            <DialogTitle>Create Certificate Request</DialogTitle>
                            <DialogDescription>
                                Create a new barangay certificate request.
                            </DialogDescription>
                        </DialogHeader>

                        <div class="space-y-4">
                            <div class="grid gap-2">
                                <Label for="brgy_cert_id">Certificate Type</Label>
                                <Combobox
                                    v-model="createCertId"
                                    :items="certificateItems"
                                    placeholder="Select a certificate type"
                                    search-placeholder="Search certificates..."
                                    empty-message="No certificates found."
                                />
                                <input type="hidden" name="brgy_cert_id" :value="createCertId ?? ''" />
                                <InputError :message="errors.brgy_cert_id" />
                            </div>

                            <div class="grid gap-2">
                                <Label for="requested_by">Requested By</Label>
                                <Combobox
                                    v-model="createResidentId"
                                    :items="residentItems"
                                    placeholder="Select a resident"
                                    search-placeholder="Search residents..."
                                    empty-message="No residents found."
                                    :disabled="isResident"
                                />
                                <input type="hidden" name="requested_by" :value="isResident ? authUser?.id : createResidentId ?? ''" />
                                <InputError :message="errors.requested_by" />
                            </div>

                            <div class="grid gap-2">
                                <Label for="status">Status</Label>
                                <select
                                    id="status"
                                    name="status"
                                    class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-xs transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-hidden focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50"
                                    required
                                >
                                    <option value="new">New</option>
                                    <option value="pending">Pending</option>
                                    <option value="processing">Processing</option>
                                    <option value="ready_to_pick_up">Ready to Pick Up</option>
                                </select>
                                <InputError :message="errors.status" />
                            </div>

                            <div class="grid gap-2">
                                <Label for="pick_up_at">Pick Up Date</Label>
                                <Input
                                    id="pick_up_at"
                                    name="pick_up_at"
                                    type="date"
                                />
                                <InputError :message="errors.pick_up_at" />
                            </div>
                        </div>

                        <DialogFooter class="gap-2">
                            <DialogClose as-child>
                                <Button
                                    variant="secondary"
                                    @click="
                                        () => {
                                            clearErrors();
                                            reset();
                                        }
                                    "
                                >
                                    Cancel
                                </Button>
                            </DialogClose>

                            <Button type="submit" :disabled="processing">
                                Create Request
                            </Button>
                        </DialogFooter>
                    </Form>
                </DialogContent>
            </Dialog>
        </div>

        <!-- Certificate Requests Table -->
        <div class="rounded-lg border overflow-hidden">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b bg-muted/50">
                        <th class="h-10 px-4 text-left font-medium text-muted-foreground w-12">#</th>
                        <th class="h-10 px-4 text-left font-medium text-muted-foreground">Certificate</th>
                        <th class="h-10 px-4 text-left font-medium text-muted-foreground">Requested By</th>
                        <th class="h-10 px-4 text-left font-medium text-muted-foreground">Status</th>
                        <th class="h-10 px-4 text-left font-medium text-muted-foreground">Pick Up Date</th>
                        <th class="h-10 px-4 text-right font-medium text-muted-foreground w-44">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="certificateRequests.length === 0">
                        <td colspan="6" class="py-8 text-center text-sm text-muted-foreground">
                            No certificate requests yet.
                        </td>
                    </tr>

                    <tr
                        v-for="(request, index) in certificateRequests"
                        :key="request.id"
                        class="border-b last:border-b-0"
                    >
                        <td class="p-4 font-medium">{{ index + 1 }}</td>
                        <td class="p-4 font-medium">
                            {{ request.brgy_cert_request?.cert_name || '\u2014' }}
                        </td>
                        <td class="p-4 text-muted-foreground">
                            {{ request.request_by?.name || '\u2014' }}
                        </td>
                        <td class="p-4">
                            <Badge :variant="statusVariant[request.status] || 'secondary'">
                                {{ statusLabel[request.status] || request.status }}
                            </Badge>
                        </td>
                        <td class="p-4 text-muted-foreground">
                            {{ formatDate(request.pick_up_at) }}
                        </td>
                        <td class="p-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <Dialog>
                                    <DialogTrigger as-child>
                                        <Button variant="outline" size="sm">
                                            Edit
                                        </Button>
                                    </DialogTrigger>

                                    <DialogContent>
                                        <Form
                                            v-bind="RequestController.update.form({ certificate_request: request.id })"
                                             resetOnSuccess
                                            class="space-y-6"
                                            v-slot="{ errors, processing }"
                                        >
                                            <DialogHeader class="space-y-3">
                                                <DialogTitle>Edit Certificate Request</DialogTitle>
                                                <DialogDescription>
                                                    Update the certificate request details.
                                                </DialogDescription>
                                            </DialogHeader>

                                            <div class="space-y-4">
                                                <div class="grid gap-2">
                                                    <Label for="edit_brgy_cert_id">Certificate Type</Label>
                                                    <Combobox
                                                        :model-value="getEditCertId(request.id) ?? request.brgy_cert_id"
                                                        :items="certificateItems"
                                                        placeholder="Select a certificate type"
                                                        search-placeholder="Search certificates..."
                                                        empty-message="No certificates found."
                                                        @update:model-value="(val) => setEditCertId(request.id, val)"
                                                    />
                                                    <input type="hidden" name="brgy_cert_id" :value="getEditCertId(request.id) ?? request.brgy_cert_id" />
                                                    <InputError :message="errors.brgy_cert_id" />
                                                </div>

                                                <div class="grid gap-2">
                                                    <Label for="edit_requested_by">Requested By</Label>
                                                    <Combobox
                                                        :model-value="getEditResidentId(request.id) ?? request.requested_by"
                                                        :items="residentItems"
                                                        placeholder="Select a resident"
                                                        search-placeholder="Search residents..."
                                                        empty-message="No residents found."
                                                        :disabled="isResident"
                                                        @update:model-value="(val) => setEditResidentId(request.id, val)"
                                                    />
                                                    <input type="hidden" name="requested_by" :value="getEditResidentId(request.id) ?? request.requested_by" />
                                                    <InputError :message="errors.requested_by" />
                                                </div>

                                                <div class="grid gap-2">
                                                    <Label for="edit_status">Status</Label>
                                                    <select id="edit_status" name="status" class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-xs transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-hidden focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50" :value="request.status" required>
                                                        <option value="new">New</option>
                                                        <option value="pending">Pending</option>
                                                        <option value="processing">Processing</option>
                                                        <option value="ready_to_pick_up">Ready to Pick Up</option>
                                                    </select>
                                                    <InputError :message="errors.status" />
                                                </div>

                                                <div class="grid gap-2">
                                                    <Label for="edit_pick_up_at">Pick Up Date</Label>
                                                    <Input id="edit_pick_up_at" name="pick_up_at" type="date" :model-value="request.pick_up_at ? request.pick_up_at.split('T')[0] : ''" />
                                                    <InputError :message="errors.pick_up_at" />
                                                </div>
                                            </div>

                                            <DialogFooter class="gap-2">
                                                <DialogClose as-child>
                                                    <Button variant="secondary" type="button">Cancel</Button>
                                                </DialogClose>
                                                <Button type="submit" :disabled="processing">
                                                    Save Changes
                                                </Button>
                                            </DialogFooter>
                                        </Form>
                                    </DialogContent>
                                </Dialog>
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
                                                <strong>&ldquo;{{ request.brgy_cert_request?.cert_name || 'Unknown' }}&rdquo;</strong>?
                                                This action cannot be undone.
                                            </DialogDescription>
                                        </DialogHeader>

                                        <Form
                                            v-bind="RequestController.destroy.form({ certificate_request: request.id })"
                                            v-slot="{ processing }"
                                        >
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
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>