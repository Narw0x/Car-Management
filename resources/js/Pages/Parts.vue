<script setup>

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import { defineProps, ref, onMounted } from 'vue';
import axios from 'axios';

const parts = ref([]);


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


// Search parts
const searchData = ref('');
const loaded = ref(true);
const searchCParts = async () => {
    if(searchData.value === '') {
        loaded.value = true;
        return;
    }

    await axios.get(route('parts.search', {search:searchData.value }))
    .then(response => {
        loaded.value = false;
        parts.value = response.data.parts;
    })
    .catch(error => {
        console.log(error);
    });
}

// Delete part
const deleteMessage = ref(null); 
const isMessageVisible = ref(false);
const deletePart = async (id) => {
    await axios.delete(route('dashboard.parts.destroy', {id: id}))
    .then(response => {
        if(response.data.status === 'success') {
            parts.value = parts.value.filter(parts => parts.id !== id);

            console.log(parts.value);
            
            
            deleteMessage.value = response.data.message;
            isMessageVisible.value = true;
            setTimeout(() => {
                isMessageVisible.value = false;
            }, 5000);
        }
    })
}

//Props
const props = defineProps({
  errors: Object,
});

</script>

<template>
    <Head title="Parts" />
    <AuthenticatedLayout>
        <template #header>
            <div class="d-flex justify-content-between">
                <h2 class="fw-bold">Parts</h2>
            </div>
        </template>
        <div v-if="isVisible" class="alert alert-success container text-center my-8">
            {{ message }}
        </div>
        <div v-if="isMessageVisible" class="container text-center alert alert-success my-8">
            {{ deleteMessage }}
        </div>
        <div v-if="props.errors.error" class="container text-center alert alert-danger my-8">
            <ul>
                <li v-for="error in props.errors" :key="error">
                    {{ error }}
                </li>
            </ul>
        </div>
        <div class="container d-grid grid-cols-1 gap-4 mt-8 bg-body p-4 rounded shadow">
            <form class="d-flex flex-col gap-2">
                <label for="car-name" class="fw-bold">Search parts by name</label>
                <div class="d-flex flex-row gap-2">
                   <input
                        id="car-name"
                        type="text"
                        class="form-control"
                        placeholder="Search parts"
                        v-model="searchData"
                    /> 
                    <button class="btn btn-primary" type="button" @click="searchCParts">
                        Search
                    </button>
                </div>
            </form>
        </div>
        <div v-if="!loaded" class="container grid grid-cols-1 gap-4 mt-8 bg-body p-4 rounded shadow">
            <div v-if="parts.length > 0 && !loaded">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Part Name</th>
                            <th>Serial Number</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="part in parts" :key="part.id">
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
            <div v-else class="d-flex justify-content-center my-auto">
                <p class="text-center m-0">No parts found</p>
            </div>
        </div>
    </AuthenticatedLayout>
</template>