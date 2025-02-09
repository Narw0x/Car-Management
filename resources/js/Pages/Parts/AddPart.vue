<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, defineProps } from 'vue';
import axios from 'axios';

const props = defineProps({
  id: String,
});


const name = ref('');
const serial_number = ref('');

let errorMessage = ref('');

const car = ref([]);
const getCar = async () => {
    await axios.get(route('car.byId', {id: props.id}))
    .then(response => {
        car.value = response.data.car;
    })
    .catch(error => {
        console.log(error);
    })
}

getCar();


const addPart = async () => {
    const data = {
        name: name.value,
        serial_number: serial_number.value,
        car_id: +props.id
    }

    await axios.post(route('dashboard.parts.store'), data)
    .then(response => {
        if(response.data.status === 'success') {
            errorMessage.value = '';
            name.value = '';
            serial_number.value = '';
            
            window.location.href = route('dashboard.cars.show', {id:+props.id,  message: response.data.message });
        }
        if(response.data.status === 'error') {
            console.log(response.data);
            errorMessage.value = response.data.message;
        }
    })
    .catch(error => {
        console.log(error);
    })
}



const pageBack = () => {
    window.location.href = route('dashboard.cars');
}
</script>

<template>
    <Head title="Parts" />
    <AuthenticatedLayout>
        <template #header>
            <div class="d-flex justify-content-between">
                <h2
                    class="fw-bold"
                >
                    Parts
                </h2>
            </div>
        </template>

        <div v-if="errorMessage" class="container text-center alert alert-danger my-8">
            {{ errorMessage }}
        </div>


        <div class="container grid grid-cols-1 gap-4 mt-16 bg-body p-4 rounded shadow">
            <h2 class="fw-bold">Add Part</h2>
            <div v-if="car">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Car Name</th>
                            <th>Registration Number</th>
                            <th>Is Registered</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr :key="car.id">
                            <td>{{ car.name }}</td>
                            <td>{{ car.registration_number }}</td>
                            <td>{{ car.is_registered ? 'Yes' : 'No' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <form class="d-flex flex-col gap-2" @submit.prevent="addPart">
                <div class="d-flex flex-column gap-2 mb-3">
                    <div>
                        <label for="name">Part Name</label>
                        <input
                            type="text"
                            id="name"
                            v-model="name"
                            class="form-control"
                        >
                    </div>
                    <div>
                        <label for="serial_number">Serial Number</label>
                        <input
                            type="text"
                            id="serial_number"
                            v-model="serial_number"
                            class="form-control"
                        >
                    </div>

                </div>
                <div class="d-flex gap-2 ">
                    <button class="btn btn-secondary" @click="pageBack">
                        Back
                    </button>
                    <button class="btn btn-primary" >
                        Add part
                    </button>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>