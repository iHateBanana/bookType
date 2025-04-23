<template>
    <Head title="Select Book" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Select a Book
            </h2>
        </template>

        <!-- Error Handling -->
        <div v-if="error" class="alert alert-error">
            <p>{{ error }}</p>
        </div>

        <!-- Main Content -->
        <div class="container mx-auto p-4">
            <input
                type="text"
                v-model="searchQuery"
                class="p-2 border border-gray-300 rounded mb-6 w-full md:w-1/2"
                placeholder="Search for a book..."
                @input="searchBooks"
            />

            <!-- Loading or No Books Found -->
            <div v-if="loading" class="text-gray-600">Loading books...</div>
            <div v-else-if="books.length === 0" class="text-gray-600">No books found.</div>

            <!-- Book Grid -->
            <div v-else class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                <div
                    v-for="book in books"
                    :key="book.id"
                    class="border w-full p-4 rounded shadow-md hover:shadow-xl transition cursor-pointer bg-white"
                    @click="goToPractice(book)"
                >
                    <img
                        :src="book.cover_url || '/images/default-cover.jpg'"
                        alt="Book cover"
                        class="w-full h-48 object-cover rounded mb-4"
                    />
                    <h2 class="text-lg font-semibold text-black dark:text-black">{{ book.title }}</h2>
                    <p class="text-gray-600 dark:text-gray-400">{{ book.author }}</p>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { fetchBooks } from '@/gutenbergApi'; // Adjust path if needed
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

// Reactive references for books, search query, and loading state
const books = ref([]);
const searchQuery = ref('');
const loading = ref(false);
const error = ref(''); // Added error state if needed

// Fetch books based on search query
const searchBooks = async () => {
    loading.value = true;
    try {
        books.value = await fetchBooks(searchQuery.value);
    } catch (err) {
        error.value = 'Failed to load books';
    } finally {
        loading.value = false;
    }
};

// Load initial set of books on mounted
onMounted(() => {
    searchBooks();
});

// Go to the practice page for the selected book
const goToPractice = (book) => {
    Inertia.visit(route('typing.practice', { book: book.id }));
};
</script>
