<template>
    <div>
        <h1 class="text-2xl font-bold mb-6">{{ book.title }} - Practice</h1>

        <div v-if="bookText">
            <pre class="bg-gray-100 p-4 rounded">
                {{ bookText }}
            </pre>

            <textarea v-model="userInput" @input="checkTyping" rows="5" cols="50"></textarea>

            <p>WPM: {{ wpm }} | Accuracy: {{ accuracy.toFixed(2) }}%</p>
            <button @click="saveSession" class="mt-4 px-4 py-2 bg-blue-600 text-white rounded">
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
    currentText.value = book.value ? book.value.text.substring(0, chunkSize) : '';
    userInput.value = '';
    startTime = null;
});

function nextChunk() {
    currentOffset.value += chunkSize;
    currentText.value = book.value.text.substring(currentOffset.value, currentOffset.value + chunkSize);
    userInput.value = '';
    startTime = null;
}

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

    // If the chunk is complete
    if (typed.length >= currentText.value.length) {
        saveSession();
        nextChunk();
    }
}

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
