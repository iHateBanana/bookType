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
import {ref, onMounted} from 'vue';
import axios from 'axios';

const props = defineProps({
    book: {
        type: Object,
        required: true,
    },
    bookText: {
        type: String,
        required: true,
    },
});

const book = ref(props.book);
const userInput = ref('');
const currentText = ref('');
const wpm = ref(0);
const accuracy = ref(0);
let startTime = null;

const chunkSize = 500;
const currentOffset = ref(0);

// Initialize book text on mounted
onMounted(() => {
    loadNextChunk();
});

// Function to load next chunk of text
function loadNextChunk() {
    const textLength = book.value.text.length;
    const endOffset = currentOffset.value + chunkSize;

    if (currentOffset.value < textLength) {
        currentText.value = book.value.text.substring(currentOffset.value, Math.min(endOffset, textLength));
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

    // If the chunk is complete, save and load next chunk
    if (typed.length >= currentText.value.length) {
        saveSession();
        currentOffset.value += chunkSize;
        loadNextChunk();
    }
}

// Finish practice and save the session
async function finishPractice() {
    await saveSession();
    alert("Practice session completed!");
}

// Save session data
async function saveSession() {
    try {
        await axios.post('/sessions', {
            book_id: book.value.id,
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
