<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import axios from 'axios';

// Props
const props = defineProps({
  car: Object,
});

// Init
const is_registered = ref(props.car.is_registered);
const registration_number = ref(props.car.registration_number);
const name = ref(props.car.name);
const errorMessage = ref('');

// Update car
const updateCar = async () => {
    const data = {
        name: name.value,
        is_registered: is_registered.value,
        registration_number: is_registered.value ? registration_number.value : ''
    }
    await axios.patch(route('dashboard.cars.update', {id: props.car.id}), data)
    .then(response => {
        if(response.data.status === 'success') {
            errorMessage.value = '';
            name.value = '';
            is_registered.value = false;
            registration_number.value = '';
            window.location.href = route('dashboard.cars', { message: response.data.message });
        }
        if(response.data.status === 'error') {
            errorMessage.value = response.data.message;
        }
    })
    .catch(error => {
        console.log(error);
    })
}

const pageBack = () => window.location.href = route('dashboard.cars');
</script>

<template>
    <Head title="Edit Car" />
    <AuthenticatedLayout>
        <template #header>
            <div class="d-flex justify-content-between">
                <h2 class="fw-bold">Cars</h2>
            </div>
        </template>
        <div v-if="errorMessage" class="container text-center alert alert-danger my-8">
            {{ errorMessage }}
        </div>
        <div class="container grid grid-cols-1 gap-4 mt-16 bg-body p-4 rounded shadow">
            <form class="d-flex flex-col gap-2" @submit.prevent="updateCar">
                <div class="d-flex flex-column gap-2">
                    <label for="name" class="form-label fw-bold">Car Name</label>
                    <input
                        id="name"
                        type="text"
                        class="form-control"
                        placeholder="Enter car name"
                        v-model="name"
                        required
                    />
                </div>
                <div class="" v-if="is_registered">
                    <label for="registration_number" class="form-label fw-bold">Registration Number</label>
                    <input 
                    v-model="registration_number" 
                    type="text" 
                    id="registration_number" 
                    class="form-control" 
                    placeholder="Enter registration number" 
                    :required="is_registered"
                    />
                </div>
                <div class="mb-3 d-flex gap-2">
                    <label for="is_registered" class="form-label fw-bold">Is Registered</label>
                    <input 
                        v-model="is_registered" 
                        type="checkbox" 
                        id="is_registered" 
                        class="form-check-input" 

                    />
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-secondary" @click="pageBack">
                        Back
                    </button>
                    <button class="btn btn-primary">
                        Edit Car
                    </button>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>