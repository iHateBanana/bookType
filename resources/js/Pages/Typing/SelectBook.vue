<template>
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-6">Select a Book for Practice</h1>

        <!-- Search/Filter Input -->
        <input
            type="text"
            v-model="searchQuery"
            class="p-2 border border-gray-300 rounded mb-6 w-full md:w-1/2"
            placeholder="Search for a book by title or author..."
        />

        <!-- Book Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            <div
                v-for="book in filteredBooks"
                :key="book.id"
                class="border p-4 rounded shadow-md hover:shadow-xl transition cursor-pointer bg-white dark:bg-gray-800"
                @click="goToPractice(book)"
            >
                <img :src="book.cover_url" alt="Book cover" class="w-full h-48 object-cover rounded mb-4" />
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ book.title }}</h2>
                <p class="text-gray-600 dark:text-gray-400">{{ book.author }}</p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Inertia } from '@inertiajs/inertia';

defineProps({
    books: Array,
});

const searchQuery = ref('');

// Filter books based on search query
const filteredBooks = computed(() => {
    if (!searchQuery.value.trim()) return books;

    return books.filter((book) => {
        const title = book.title.toLowerCase();
        const author = book.author.toLowerCase();
        const query = searchQuery.value.toLowerCase();
        return title.includes(query) || author.includes(query);
    });
});

// Redirect to typing practice
function goToPractice(book) {
    Inertia.visit(route('typing.practice', { book: book.id }));
}
</script>

<style scoped>
/* Add any desired hover or responsive effects here */
</style>
