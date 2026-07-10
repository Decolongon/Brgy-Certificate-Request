<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { dashboard, login } from '@/routes';
import { register } from '@/routes';
import { BadgeCheck, ClipboardList, FileText, ScrollText } from '@lucide/vue';
</script>

<template>
    <Head title="Welcome" />

    <div class="flex min-h-screen flex-col bg-background text-foreground">
        <!-- Navbar -->
        <header class="sticky top-0 z-50 w-full border-b border-border bg-background/80 backdrop-blur-sm">
            <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-6">
                <div class="flex items-center gap-3">
                    <div
                        class="flex h-9 w-9 items-center justify-center rounded-lg bg-primary text-primary-foreground text-sm font-bold">
                        B
                    </div>
                    <span class="text-lg font-semibold">BRGY</span>
                </div>

                <nav class="flex items-center gap-4">
                    <Link v-if="$page.props.auth.user" :href="dashboard()"
                        class="inline-flex items-center justify-center rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground shadow transition-colors hover:bg-primary/90">
                        Dashboard
                    </Link>
                    <template v-else>
                        <Link :href="login()"
                            class="text-sm font-medium text-muted-foreground transition-colors hover:text-foreground dark:text-muted-foreground-dark dark:hover:text-foreground-dark">
                            Log in
                        </Link>
                        <Link :href="register()"
                            class="inline-flex items-center justify-center rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground shadow transition-colors hover:bg-primary/90 dark:bg-primary-dark dark:text-primary-foreground-dark dark:hover:bg-primary-dark/90">
                            Register
                        </Link>
                    </template>
                </nav>
            </div>
        </header>

        <!-- Hero Section -->
        <section class="flex-1">
            <div class="mx-auto max-w-7xl px-6 py-20 lg:py-32">
                <div class="mx-auto max-w-3xl text-center">
                    <h1 class="text-4xl font-bold tracking-tight sm:text-5xl lg:text-6xl">
                        Barangay Certificate
                        <span class="text-primary">Request System</span>
                    </h1>
                    <p class="mt-6 text-lg leading-8 text-muted-foreground">
                        A streamlined way for residents to request and manage barangay certificates online.
                        No more long queues &mdash; submit your requests anytime, anywhere.
                    </p>
                    <div class="mt-10 flex items-center justify-center gap-4">
                        <Link v-if="!$page.props.auth.user" :href="register()"
                            class="inline-flex items-center justify-center rounded-md bg-primary px-6 py-3 text-sm font-medium text-primary-foreground shadow transition-colors hover:bg-primary/90">
                            Get Started
                        </Link>
                        <Link v-if="$page.props.auth.user" :href="dashboard()"
                            class="inline-flex items-center justify-center rounded-md bg-primary px-6 py-3 text-sm font-medium text-primary-foreground shadow transition-colors hover:bg-primary/90">
                            Go to Dashboard
                        </Link>
                        <Link :href="login()"
                            class="inline-flex items-center justify-center rounded-md border border-input bg-background px-6 py-3 text-sm font-medium shadow-sm transition-colors hover:bg-accent hover:text-accent-foreground dark:border-input-dark dark:bg-background-dark dark:hover:bg-accent-dark dark:hover:text-accent-foreground-dark">
                            Sign In
                        </Link>
                    </div>
                </div>
            </div>
        </section>

        <!-- About Barangay Alicante Section -->
        <section class="py-16 bg-background">
            <div class="mx-auto max-w-7xl px-6">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold tracking-tight sm:text-4xl">
                        About Barangay Alicante
                    </h2>
                </div>
                <div class="max-w-4xl mx-auto text-center">
                    <p class="text-lg leading-8 text-muted-foreground">
                        Barangay Alicante in Enrique B. Magalona (formerly Saravia), Negros Occidental, is a primarily coastal and fishing community located along the Guimaras Strait. Home to over 7,000 residents, it is famous for its 42-hectare century-old mangrove forest in Sitio Buyog, which serves as a sanctuary for wild ducks and birds.
                    </p>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section class="bg-accent/50 py-16">
            <div class="mx-auto max-w-7xl px-6">
                <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
                    <div class="rounded-lg bg-background p-6 shadow-sm">
                        <div
                            class="mb-4 flex h-12 w-12 items-center justify-center rounded-lg bg-primary text-primary-foreground">
                            <ClipboardList class="h-6 w-6" />
                        </div>
                        <h3 class="text-lg font-semibold">Easy Requests</h3>
                        <p class="mt-2 text-muted-foreground">
                            Submit your barangay certificate requests online with just a few clicks.
                        </p>
                    </div>
                    <div class="rounded-lg bg-background p-6 shadow-sm">
                        <div
                            class="mb-4 flex h-12 w-12 items-center justify-center rounded-lg bg-primary text-primary-foreground">
                            <FileText class="h-6 w-6" />
                        </div>
                        <h3 class="text-lg font-semibold">Track Progress</h3>
                        <p class="mt-2 text-muted-foreground">
                            Monitor the status of your requests in real-time.
                        </p>
                    </div>
                    <div class="rounded-lg bg-background p-6 shadow-sm">
                        <div
                            class="mb-4 flex h-12 w-12 items-center justify-center rounded-lg bg-primary text-primary-foreground">
                            <BadgeCheck class="h-6 w-6" />
                        </div>
                        <h3 class="text-lg font-semibold">Secure & Reliable</h3>
                        <p class="mt-2 text-muted-foreground">
                            Your data is protected with industry-standard security measures.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- How It Works Section -->
        <section class="py-16 bg-accent/10">
            <div class="mx-auto max-w-7xl px-6">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold tracking-tight sm:text-4xl">
                        How It Works
                    </h2>
                    <p class="mt-4 text-lg text-muted-foreground">
                        Get your barangay certificate in just 3 simple steps.
                    </p>
                </div>
                <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
                    <div class="rounded-lg bg-background p-6 shadow-sm text-center">
                        <div class="flex justify-center mb-4">
                            <div
                                class="flex h-12 w-12 items-center justify-center rounded-full bg-primary text-primary-foreground text-lg font-bold">
                                1
                            </div>
                        </div>
                        <h3 class="text-lg font-semibold">Submit Request</h3>
                        <p class="mt-2 text-muted-foreground">
                            Fill out the online form with your details and certificate requirements.
                        </p>
                    </div>
                    <div class="rounded-lg bg-background p-6 shadow-sm text-center">
                        <div class="flex justify-center mb-4">
                            <div
                                class="flex h-12 w-12 items-center justify-center rounded-full bg-primary text-primary-foreground text-lg font-bold">
                                2
                            </div>
                        </div>
                        <h3 class="text-lg font-semibold">Wait for Processing</h3>
                        <p class="mt-2 text-muted-foreground">
                            Our team will verify your information and prepare your certificate.
                        </p>
                    </div>
                    <div class="rounded-lg bg-background p-6 shadow-sm text-center">
                        <div class="flex justify-center mb-4">
                            <div
                                class="flex h-12 w-12 items-center justify-center rounded-full bg-primary text-primary-foreground text-lg font-bold">
                                3
                            </div>
                        </div>
                        <h3 class="text-lg font-semibold">Receive Certificate</h3>
                        <p class="mt-2 text-muted-foreground">
                            Download your certificate digitally or pick it up at the barangay hall.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="border-t border-border bg-background py-8">
            <div class="mx-auto max-w-7xl px-6">
                <div class="flex flex-col items-center justify-between md:flex-row">
                    <div class="text-sm text-muted-foreground">
                        © 2026 Barangay Certificate Request System. All rights reserved.
                    </div>
                    <div class="mt-4 flex gap-4 md:mt-0">
                        <Link href="#" class="text-sm text-muted-foreground hover:text-foreground">
                            Terms of Service
                        </Link>
                        <Link href="#" class="text-sm text-muted-foreground hover:text-foreground">
                            Privacy Policy
                        </Link>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>