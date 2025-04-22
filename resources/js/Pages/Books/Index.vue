<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Test</h2>
        </template>
    <div>
        <h1 class="text-3xl font-semibold mb-6">Select a Book</h1>

        <!-- Display any errors or success messages -->
        <div v-if="flashMessage" class="mb-4 p-4 bg-green-500 text-white rounded">
            {{ flashMessage }}
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Loop through books and display them -->
            <div v-for="book in books" :key="book.id" class="card shadow-md p-4 rounded-lg bg-white">
                <h2>{{ book.title }}</h2>
                <p>{{ book.author }}</p>
                <img :src="book.cover_url" alt="Book Cover" />

                <button
                    @click="selectBook(book.id)"
                    class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600"
                >
                    Select for Practice
                </button>
            </div>
        </div>
    </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref } from 'vue';
import {Head, usePage} from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
const goToPractice = (book) => {
    router.push(route('typing.practice', { book: book.id }));
};


const { props } = usePage();

// List of books passed from the backend
const books = props.books || [];

// Flash message for success or other feedback (if passed from the backend)
const flashMessage = props.flash?.message;

// Function to redirect to the typing practice page with the selected book
const selectBook = (bookId) => {
    router.get(route('typing.practice', { book: bookId }));
};

</script>

<style scoped>
.card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}
</style>
