<script setup>

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import { defineProps, ref, onMounted } from 'vue';
import axios from 'axios';

const cars = ref([]);

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


// Search cars
const loaded = ref(true);
const searchData = ref('');
const searchCars = async () => {
    if(searchData.value === '') {
        loaded.value = true;
        return;
    }
    await axios.get(route('cars.search', {search:searchData.value }))
    .then(response => {
        cars.value = response.data.cars;
        loaded.value = false;
    })
    .catch(error => {
        console.log(error);
    });
}

// Delete car
const deleteMessage = ref(null); 
const isMessageVisible = ref(false);
const deleteCar = async (id) => {
    await axios.delete(route('dashboard.cars.destroy', {id: id}))
    .then(response => {
        if(response.data.status === 'success') {
            cars.value = cars.value.filter(car => car.id !== id);
            deleteMessage.value = response.data.message;
            isMessageVisible.value = true;
            setTimeout(() => {
                isMessageVisible.value = false;
            }, 5000);
        }
    })
}

// Props
const props = defineProps({
  errors: Object,
});

</script>

<template>
     <Head title="Cars" />

    <AuthenticatedLayout>
        <template #header>
            <div class="d-flex justify-content-between">
                <h2 class="fw-bold">Cars</h2>
                <Link
                    :href="route('dashboard.cars.add')"
                >
                    <button class="btn btn-primary">Add Car</button>
                </Link>
            </div>
        </template>
        <div v-if="isVisible" class="alert alert-success container text-center mt-8 mb-0">
            {{ message }}
        </div>
        <div v-if="isMessageVisible" class="container text-center alert alert-success mt-8 mb-0">
            {{ deleteMessage }}
        </div>
        <div v-if="props.errors.error" class="container text-center alert alert-danger mt-8 mb-0">
            <ul>
                <li v-for="error in props.errors" :key="error">
                    {{ error }}
                </li>
            </ul>
        </div>
        <div class="container grid grid-cols-1 gap-4 mt-8 bg-body p-4 rounded shadow">
            <form class="d-flex flex-col gap-2">
                <label for="car-name" class="fw-bold">Search cars by name</label>
                <div class="d-flex flex-row gap-2">
                   <input
                        id="car-name"
                        type="text"
                        class="form-control"
                        placeholder="Search cars"
                        v-model="searchData"
                    /> 
                    <button class="btn btn-primary" type="button" @click="searchCars">
                        Search
                    </button>
                </div>
            </form>
        </div>
        <!-- show cars -->
        <div v-if="!loaded" class="container grid grid-cols-1 gap-4 mt-8 bg-body p-4 rounded shadow">
            <div v-if="cars.length > 0 && !loaded">
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
                        <tr v-for="car in cars" :key="car.id">
                            <td>{{ car.name }}</td>
                            <td>{{ car.registration_number }}</td>
                            <td>{{ car.is_registered ? 'Yes' : 'No' }}</td>
                            <td class="d-flex gap-2 justify-content-end">
                                <Link 
                                    :href="route('dashboard.cars.show', {id: car.id})"
                                >
                                    <button class="btn btn-primary" type="button">View</button>
                                </Link>
                                <Link
                                    :href="route('dashboard.cars.edit', {id: car.id})"
                                >
                                    <button class="btn btn-warning" type="button">Edit</button>
                                </Link>
                                <button class="btn btn-danger" type="button" @click="deleteCar(car.id)">Delete</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div v-else class="d-flex justify-content-center my-auto">
                <p class="text-center m-0">No cars found</p>
            </div>
        </div>
    </AuthenticatedLayout>
</template>