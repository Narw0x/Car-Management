<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { defineProps } from 'vue';

import Cards from '@/Components/Cards.vue';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
});

</script>

<template>
    <Head title="Welcome" />
    <header class="border-bottom p-3 bg-body">
        <div class="d-flex justify-content-between  container py-3">
            <div >
                <Link href="/" class="text-decoration-none text-dark ">
                    Car Management System
                </Link>
            </div>
            <nav v-if="canLogin" class="d-flex gap-4"> 
                <Link
                    v-if="$page.props.auth.user"
                    :href="route('dashboard')"
                    class="text-decoration-none text-dark "
                >
                    Dashboard
                </Link>

                <template v-else>
                    <Link
                        :href="route('login')"
                        class="text-decoration-none text-dark "
                    >
                        Log in
                    </Link>

                    <Link
                        v-if="canRegister"
                        :href="route('register')"
                        class="text-decoration-none text-dark "
                    >
                        Register
                    </Link>
                </template>
            </nav>
        </div>  
    </header>
    <main class="bg-light p-5 h-screen">
        <div class="bg-body container border p-5 rounded shadow mb-5 bg-body ">
            <h1 class="text-center">Welcome to Car Management System</h1>
            <p class="text-center">This is a simple car management system built with Laravel and Vue.js</p>
        </div>
        <div class="container grid grid-cols-1 gap-4 md:grid-cols-2 px-0 ">
            <Cards
                :href="route('dashboard.cars')"
            >
                <h2 class="text-center">Cars</h2>
                <p class="text-center">Find cars of your choice.</p>
            </Cards>

            <Cards
                :href="route('dashboard.parts')"
            >
                <h2 class="text-center">Parts</h2>
                <p class="text-center">Find parts of your choice.</p>
            </Cards>
            
        </div>
    </main>
    
</template>
