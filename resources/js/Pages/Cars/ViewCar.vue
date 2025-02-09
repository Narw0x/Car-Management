<script setup>

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import { defineProps, ref, onMounted } from 'vue';
import axios from 'axios';

// Props
const props = defineProps({
  car: Object,
});

// Message
const message = ref(null);
const isVisible = ref(false);
onMounted(() => {
    const urlParams = new URLSearchParams(window.location.search);
    const urlMessage = urlParams.get('message');

    if (urlMessage) {
        message.value = urlMessage;
        isVisible.value = true;
        setTimeout(() => {
            isVisible.value = false;
        }, 5000);
    }
});


// Delete car
const deleteCar = async (id) => {
    await axios.delete(route('dashboard.cars.destroy', {id: id}))
    .then(response => {
        if(response.data.status === 'success') {
            window.location.href = route('dashboard.cars', { message: response.data.message });
        }
    })
}

// Delete part
const deletePart = async (id) => {
    await axios.delete(route('dashboard.parts.destroy', {id: id}))
    .then(response => {
        if(response.data.status === 'success') {
            window.location.href = route('dashboard.cars', { message: response.data.message });
        }
    })
}

</script>

<template>
     <Head title="View Car" />
    <AuthenticatedLayout>
        <template #header>
            <div class="d-flex justify-content-between">
                <h2 class="fw-bold">Cars</h2>
                <div class="d-flex gap-2">
                    <Link
                        :href="route('dashboard.cars')"
                    >
                        <button class="btn btn-secondary">Back</button>
                    </Link>
                    <Link
                        :href="route('dashboard.parts.add', {id: props.car.id})"
                    >
                        <button class="btn btn-primary">Add Part</button>
                    </Link>
                </div>
            </div>
        </template>
        <div v-if="isVisible" class="alert alert-success container text-center my-8">
            {{ message }}
        </div>
        <div class="container grid grid-cols-1  mt-16 bg-body p-4 rounded shadow">
            <h2 class="fw-bold">Car Details</h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Car Name</th>
                        <th>Registration Number</th>
                        <th>Is Registered</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr :key="props.car.id">
                        <td>{{ props.car.name }}</td>
                        <td>{{ props.car.registration_number }}</td>
                        <td>{{ props.car.is_registered ? 'Yes' : 'No' }}</td>
                        <td class="d-flex gap-2 justify-content-end">
                            <Link
                                :href="route('dashboard.cars.edit', {id: props.car.id})"
                            >
                                <button class="btn btn-warning" type="button">Edit</button>
                            </Link>
                            <button class="btn btn-danger" type="button" @click="deleteCar(props.car.id)">Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div>
                <h2 class="fw-bold">Parts</h2>
                <div>
                    <div v-if="props.car.parts.length > 0">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Part Name</th>
                                    <th>Serial Number</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="part in props.car.parts" :key="part.id">
                                    <td>{{ part.name }}</td>
                                    <td>{{ part.serial_number }}</td>
                                    <td class="d-flex gap-2 justify-content-end">
                                        <Link
                                            :href="route('dashboard.parts.edit', {id: part.id})"
                                        >
                                            <button class="btn btn-warning" type="button">Edit</button>
                                        </Link>
                                        <button class="btn btn-danger" type="button" @click="deletePart(part.id)">Delete</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div v-else>
                        <p class="text-center">This car has no registered parts</p>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>