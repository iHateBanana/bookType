<template>
    <div>
        <h1 class="text-3xl font-semibold mb-6">Select a Book</h1>

        <!-- Display any errors or success messages -->
        <div v-if="flashMessage" class="mb-4 p-4 bg-green-500 text-white rounded">
            {{ flashMessage }}
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Loop through books and display them -->
            <div v-for="book in books" :key="book.id" class="card shadow-md p-4 rounded-lg bg-white">
                <img :src="book.cover" alt="Book Cover" class="w-full h-48 object-cover rounded-t-md mb-4"/>
                <h3 class="font-semibold text-xl mb-2">{{ book.title }}</h3>
                <p class="text-sm text-gray-500 mb-4">{{ book.author }}</p>
                <button
                    @click="selectBook(book.id)"
                    class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600"
                >
                    Select for Practice
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';


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
