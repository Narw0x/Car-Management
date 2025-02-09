<script setup>
import { defineProps } from 'vue';
import { Link as InertiaLink } from '@inertiajs/vue3';

defineProps({
    href: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <InertiaLink :href="href" class="text-decoration-none text-dark card-hover">
        <div class="border p-5 rounded shadow bg-body">
            <slot />
        </div>
    </InertiaLink>
</template>

