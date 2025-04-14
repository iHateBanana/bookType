<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Test</h2>
        </template>

    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-6">Select a Book from Project Gutenberg</h1>

        <!-- Search Input -->
        <input
            type="text"
            v-model="searchQuery"
            class="p-2 border border-gray-300 rounded mb-6 w-full md:w-1/2"
            placeholder="Search for a book..."
            @input="searchBooks"
        />

        <!-- Book Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            <div
                v-for="book in books"
                :key="book.id"
                class="border p-4 rounded shadow-md hover:shadow-xl transition cursor-pointer bg-white"
                @click="goToPractice(book)"
            >
                <img
                    :src="book.cover_url"
                    alt="Book cover"
                    class="w-full h-48 object-cover rounded mb-4"
                />
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ book.title }}</h2>
                <p class="text-gray-600 dark:text-gray-400">{{ book.author }}</p>
            </div>
        </div>
    </div>
    </AuthenticatedLayout>
</template>

<script setup>

alert('jhjhj')
import { ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { fetchBooks } from '../gutenbergApi';
import {Head} from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue"; // Import the helper function

const books = ref([]);
const searchQuery = ref('');

// Fetch books on input change
const searchBooks = () => {
    fetchBooks(searchQuery.value).then((result) => {
        books.value = result;
    });
};

// Fetch books initially to load some data
fetchBooks('science').then((result) => {
    books.value = result;
});

// Navigate to practice page with selected book
const goToPractice = (book) => {
    Inertia.visit(route('typing.practice', { book: book.id }));
};
</script>

<style scoped>
/* Add any desired hover or responsive effects here */
</style>
