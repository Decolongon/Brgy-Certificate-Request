<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import { useTemplateRef } from 'vue';
import { type BreadcrumbItem } from '@/types';
import BrgyCertificateController from '@/actions/App/Http/Controllers/Admin/BrgyCertificateController';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
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

interface Certificate {
    id: number;
    cert_name: string;
    slug: string;
    cert_description: string | null;
}

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Brgy Certificates',
                href: BrgyCertificateController.index(),
            },
        ],
    },
});

defineProps<{
    certificates: Certificate[];
}>();

const certNameInput = useTemplateRef('certNameInput');
</script>

<template>
    <Head title="Brgy Certificates" />

    <div class="flex flex-col gap-6">
        <div class="flex items-center justify-between">
            <Heading title="Brgy Certificates" />

            <Dialog>
                <DialogTrigger as-child>
                    <Button>Create Certificate</Button>
                </DialogTrigger>

                <DialogContent>
                    <Form
                        v-bind="BrgyCertificateController.store.form()"
                        reset-on-success
                        @error="() => certNameInput?.focus()"
                        class="space-y-6"
                        v-slot="{ errors, processing, reset, clearErrors }"
                    >
                        <DialogHeader class="space-y-3">
                            <DialogTitle>Create Certificate</DialogTitle>
                            <DialogDescription>
                                Add a new barangay certificate type.
                            </DialogDescription>
                        </DialogHeader>

                        <div class="space-y-4">
                            <div class="grid gap-2">
                                <Label for="cert_name">Certificate Name</Label>
                                <Input
                                    id="cert_name"
                                    name="cert_name"
                                    ref="certNameInput"
                                    required
                                    placeholder="e.g. Barangay Clearance"
                                />
                                <InputError :message="errors.cert_name" />
                            </div>

                            <div class="grid gap-2">
                                <Label for="cert_description">Description</Label>
                                <textarea
                                    id="cert_description"
                                    name="cert_description"
                                    class="flex min-h-[60px] w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm shadow-xs placeholder:text-muted-foreground focus-visible:outline-hidden focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50"
                                    placeholder="Brief description (min. 10 characters)"
                                    rows="3"
                                />
                                <InputError :message="errors.cert_description" />
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
                                Create Certificate
                            </Button>
                        </DialogFooter>
                    </Form>
                </DialogContent>
            </Dialog>
        </div>

        <!-- Certificates Table -->
        <div class="rounded-lg border overflow-hidden">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b bg-muted/50">
                        <th class="h-10 px-4 text-left font-medium text-muted-foreground w-12">#</th>
                        <th class="h-10 px-4 text-left font-medium text-muted-foreground">Name</th>
                        <th class="h-10 px-4 text-left font-medium text-muted-foreground">Description</th>
                        <th class="h-10 px-4 text-left font-medium text-muted-foreground">Slug</th>
                        <th class="h-10 px-4 text-right font-medium text-muted-foreground w-44">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="certificates.length === 0">
                        <td colspan="5" class="py-8 text-center text-sm text-muted-foreground">
                            No certificates yet.
                        </td>
                    </tr>

                    <tr
                        v-for="(certificate, index) in certificates"
                        :key="certificate.id"
                        class="border-b last:border-b-0"
                    >
                        <td class="p-4 font-medium">{{ index + 1 }}</td>
                        <td class="p-4 font-medium">{{ certificate.cert_name }}</td>
                        <td class="p-4 text-muted-foreground max-w-xs truncate">
                            {{ certificate.cert_description || '\u2014' }}
                        </td>
                        <td class="p-4 text-muted-foreground">{{ certificate.slug }}</td>
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
                                            v-bind="
                                                BrgyCertificateController.update.form({
                                                    brgy_certificate: certificate.id,
                                                })
                                            "
                                            @error="() => certNameInput?.focus()"
                                            class="space-y-6"
                                            v-slot="{ errors, processing }"
                                        >
                                            <DialogHeader class="space-y-3">
                                                <DialogTitle>Edit Certificate</DialogTitle>
                                                <DialogDescription>
                                                    Update the barangay certificate type details.
                                                </DialogDescription>
                                            </DialogHeader>

                                            <div class="space-y-4">
                                                <div class="grid gap-2">
                                                    <Label for="edit_cert_name">Certificate Name</Label>
                                                    <Input
                                                        id="edit_cert_name"
                                                        name="cert_name"
                                                        :model-value="certificate.cert_name"
                                                        required
                                                        placeholder="e.g. Barangay Clearance"
                                                    />
                                                    <InputError :message="errors.cert_name" />
                                                </div>

                                                <div class="grid gap-2">
                                                    <Label for="edit_cert_description">Description</Label>
                                                    <textarea
                                                        id="edit_cert_description"
                                                        name="cert_description"
                                                        class="flex min-h-[60px] w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm shadow-xs placeholder:text-muted-foreground focus-visible:outline-hidden focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50"
                                                        :value="certificate.cert_description"
                                                        placeholder="Describe the purpose of this certificate..."
                                                    ></textarea>
                                                    <InputError :message="errors.cert_description" />
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
                                            <DialogTitle>Delete Certificate</DialogTitle>
                                            <DialogDescription>
                                                Are you sure you want to delete
                                                <strong>&ldquo;{{ certificate.cert_name }}&rdquo;</strong>?
                                                This action cannot be undone.
                                            </DialogDescription>
                                        </DialogHeader>

                                        <Form
                                            v-bind="
                                                BrgyCertificateController.destroy.form({
                                                    brgy_certificate: certificate.id,
                                                })
                                            "
                                            v-slot="{ processing }"
                                        >
                                            <DialogFooter class="gap-2">
                                                <DialogClose as-child>
                                                    <Button variant="secondary">Cancel</Button>
                                                </DialogClose>

                                                <Button
                                                    type="submit"
                                                    variant="destructive"
                                                    :disabled="processing"
                                                >
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
