<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import Cards from '@/Components/Cards.vue';


</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800"
            >
                Dashboard
            </h2>
        </template>

        <div class="container grid grid-cols-1 gap-4 md:grid-cols-2 px-0 pt-16">
            <Cards
                :href="route('dashboard.cars')"
            >
                <h2 class="text-center">Cars</h2>
            </Cards>

            <Cards
                :href="route('dashboard.parts')"
            >
                <h2 class="text-center">Parts</h2>
            </Cards>
            
        </div>
    </AuthenticatedLayout>
</template>
