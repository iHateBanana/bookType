<template>
    <div>
        <h1 class="text-2xl font-bold mb-6">{{ book.title }} - Practice</h1>

        <div v-if="bookText">
            <pre class="bg-gray-100 p-4 rounded">
                {{ currentText }}
            </pre>

            <textarea v-model="userInput" @input="checkTyping" rows="5" cols="50"></textarea>

            <p>WPM: {{ wpm }} | Accuracy: {{ accuracy.toFixed(2) }}%</p>
            <button @click="finishPractice" class="mt-4 px-4 py-2 bg-blue-600 text-white rounded">
                Finish Practice
            </button>

        </div>

        <div v-else>
            <p>Loading book content...</p>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

// Props passed from parent (Select page)
const props = defineProps({
    book: {
        type: Object,
        required: true,
    },
});

// Reactive variables
const userInput = ref('');
const currentText = ref('');
const wpm = ref(0);
const accuracy = ref(0);
let startTime = null;

const chunkSize = 500;
const currentOffset = ref(0);
const bookText = ref(''); // Store the fetched book text

// Fetch book text when component mounts
onMounted(async () => {
    const text = await fetchBookText(props.book.id); // Fetch text using the book's ID
    if (text) {
        bookText.value = text;
        loadNextChunk();
    } else {
        console.error('Failed to load book text');
    }
});

// Function to fetch book text from Gutendex
async function fetchBookText(bookId) {
    try {
        // Fetch metadata for the book from Gutendex
        const response = await axios.get(`https://gutendex.com/books/${bookId}/`);
        const downloadUrl = response.data.download_url;

        if (downloadUrl) {
            // Fetch the actual book text from the download URL
            const bookTextResponse = await axios.get(downloadUrl);
            return bookTextResponse.data;
        } else {
            throw new Error('Download URL not available for this book.');
        }
    } catch (error) {
        console.error('Error fetching book text:', error);
        return null;
    }
}

// Function to load next chunk of text
function loadNextChunk() {
    const textLength = bookText.value.length;
    const endOffset = currentOffset.value + chunkSize;

    if (currentOffset.value < textLength) {
        currentText.value = bookText.value.substring(currentOffset.value, Math.min(endOffset, textLength));
        userInput.value = '';
        startTime = null;
    }
}

// Function to check typing progress (accuracy & WPM)
function checkTyping() {
    if (!startTime) startTime = Date.now();

    const typed = userInput.value;
    const original = currentText.value.substring(0, typed.length);

    let correctChars = 0;
    for (let i = 0; i < typed.length; i++) {
        if (typed[i] === original[i]) correctChars++;
    }

    accuracy.value = typed.length ? (correctChars / typed.length) * 100 : 0;

    const timeElapsed = (Date.now() - startTime) / 60000;
    const words = typed.trim().split(/\s+/).length;
    wpm.value = timeElapsed ? Math.round(words / timeElapsed) : 0;

    if (typed.length >= currentText.value.length) {
        saveSession();
        currentOffset.value += chunkSize;
        loadNextChunk();
    }
}

// Save session
async function saveSession() {
    try {
        await axios.post('/sessions', {
            book_id: props.book.id,
            wpm: wpm.value,
            accuracy: accuracy.value,
            completed_text: userInput.value,
        });
        console.log('Session saved!');
    } catch (error) {
        console.error('Failed to save session:', error);
    }
}

</script>
