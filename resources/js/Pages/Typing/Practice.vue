<template>
    <div>
        <h1 class="text-2xl font-bold mb-6">{{ book.title }} - Practice</h1>

        <div v-if="bookText">
            <pre class="bg-gray-100 p-4 rounded">
                {{ bookText }}
            </pre>

            <textarea v-model="userInput" @input="checkTyping" rows="5" cols="50"></textarea>

            <p>WPM: {{ wpm }} | Accuracy: {{ accuracy.toFixed(2) }}%</p>
        </div>

        <div v-else>
            <p>Loading book content...</p>
        </div>
    </div>
</template>

<script setup>
import { defineProps } from 'vue';

const props = defineProps({
    book: Object,
    bookText: String,
});
</script>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const props = defineProps({
    book: {
        type: Object,
        default: null,
    },
});

const book = ref(props.book);
const userInput = ref('');
const currentText = ref('');
const wpm = ref(0);
const accuracy = ref(0);
let startTime = null;

onMounted(async () => {
    if (!book.value) {
        await loadDemoBook();
    }

    currentText.value = book.value
        ? book.value.text.substring(0, 500)
        : getSampleText();
});

async function loadDemoBook() {
    try {
        const response = await axios.get('/api/books/demo');
        book.value = response.data;
    } catch (error) {
        console.error('Failed to load demo book:', error);
    }
}

function getSampleText() {
    return `The quick brown fox jumps over the lazy dog. Practice typing with this sample text. Adjust the code to load books for more fun!`;
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
}
</script>
