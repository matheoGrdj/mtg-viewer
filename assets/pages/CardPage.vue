<script setup>
import { onMounted, ref } from 'vue';
import { useRoute } from 'vue-router';
import { fetchCard } from '../services/cardService';
import CardProperty from '../components/CardProperty.vue';

const route = useRoute();
const card = ref({});
const loadingCard = ref(true);

async function loadCard(uuid) {
    loadingCard.value = true;
    card.value = await fetchCard(uuid);
    loadingCard.value = false;
}

onMounted(() => {
    loadCard(route.params.uuid);
});
</script>

<template>
    <div class="card-details-container">
        <div v-if="loadingCard" class="loading-state">
            <div class="spinner" />
            <p>Chargement...</p>
        </div>

        <div v-else class="card-details">
            <div class="card-header">
                <h1>{{ card.name }}</h1>
            </div>

            <div class="card-content">
                <div class="card-properties">
                    <div class="property-grid">
                        <CardProperty name="Coût en mana" :value="card.manaCost" class="property-item" />
                        <CardProperty name="Type" :value="card.type" class="property-item" />
                        <CardProperty name="Rareté" :value="card.rarity" class="property-item" />
                        <CardProperty name="Édition" :value="card.setCode" class="property-item" />
                    </div>

                    <div class="card-text">
                        <h3>Description</h3>
                        <pre>{{ card.text }}</pre>
                    </div>
                </div>
            </div>

            <div class="card-actions">
                <router-link :to="{ name: 'all-cards' }" class="back-link">
                    ← Retourner à la liste complète
                </router-link>
            </div>
        </div>
    </div>
</template>

<style scoped>
:root {
    --primary-color: #4a6cf7;
    --secondary-color: #6b7280;
    --background-color: #f4f6f9;
    --card-background: #ffffff;
    --text-color: #1f2937;
    --border-color: #e5e7eb;
}

* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

.card-details-container {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    background-color: var(--background-color);
    padding: 2rem;
}

.loading-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: var(--secondary-color);
}

.spinner {
    border: 4px solid var(--border-color);
    border-top: 4px solid var(--primary-color);
    border-radius: 50%;
    width: 40px;
    height: 40px;
    animation: spin 1s linear infinite;
    margin-bottom: 1rem;
}

@keyframes spin {
    0% {
        transform: rotate(0deg);
    }

    100% {
        transform: rotate(360deg);
    }
}

.card-details {
    width: 100%;
    max-width: 700px;
    background-color: var(--card-background);
    border-radius: 16px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    overflow: hidden;
}

.card-header {
    background-color: var(--primary-color);
    color: white;
    padding: 1.5rem;
    text-align: center;
}

h1 {
    font-size: 1.75rem;
    font-weight: 700;
}

.card-content {
    padding: 2rem;
}

.property-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
    margin-bottom: 2rem;
}

.property-item {
    background-color: var(--background-color);
    padding: 1rem;
    border-radius: 8px;
    border: 1px solid var(--border-color);
}

.card-text {
    background-color: var(--background-color);
    padding: 1.5rem;
    border-radius: 8px;
    border: 1px solid var(--border-color);
}

.card-text h3 {
    margin-bottom: 1rem;
    color: var(--secondary-color);
}

pre {
    white-space: pre-wrap;
    word-wrap: break-word;
    font-family: inherit;
    line-height: 1.6;
    color: var(--text-color);
}

.card-actions {
    padding: 1.5rem;
    background-color: var(--background-color);
    text-align: center;
}

.back-link {
    text-decoration: none;
    color: var(--primary-color);
    font-weight: 500;
    transition: color 0.3s ease;
}

.back-link:hover {
    color: var(--secondary-color);
    text-decoration: underline;
}

@media (max-width: 600px) {
    .property-grid {
        grid-template-columns: 1fr;
    }

    .card-details {
        max-width: 100%;
        margin: 0;
    }

    .card-content {
        padding: 1rem;
    }
}
</style>
