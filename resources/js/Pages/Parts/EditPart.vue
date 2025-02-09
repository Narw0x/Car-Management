<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, defineProps } from 'vue';
import axios from 'axios';

const props = defineProps({
  part: Object,
});


const name = ref(props.part.name);
const serial_number = ref(props.part.serial_number);

let errorMessage = ref('');

const updatePart = async () => {
    const data = {
        name: name.value,
        serial_number: serial_number.value,
        car_id: +props.part.car_id
    }

    await axios.patch(route('dashboard.parts.update', {id: props.part.id}), data)
    .then(response => {
        if(response.data.status === 'success') {
            errorMessage.value = '';
            name.value = '';
            serial_number.value = '';
            
            window.location.href = route('dashboard.cars.show', { id: +props.part.car_id ,message: response.data.message });
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
    window.location.href = route('dashboard.cars.show', { id: +props.part.car_id });
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


        <div class="container grid grid-cols-1 gap-2 mt-16 bg-body p-4 rounded shadow">
            <h2 class="fw-bold">Edit Part</h2>
            <form class="d-flex flex-col gap-2" @submit.prevent="updatePart">
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
                        Edit part
                    </button>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>