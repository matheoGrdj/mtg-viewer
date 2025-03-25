<script setup>
import { onMounted, ref } from 'vue';
import { fetchAllCards, fetchSetCodes } from '../services/cardService';

const cards = ref([]);
const currentPage = ref(1);
const totalPages = ref(0);
const totalCards = ref(0);
const loadingCards = ref(true);
const selectedSetCode = ref('');
const setCodes = ref([]);

async function loadSetCodes() {
    setCodes.value = await fetchSetCodes();
}

async function loadCards(page = 1) {
    loadingCards.value = true;
    const response = await fetchAllCards(page, selectedSetCode.value);
    cards.value = response.items;
    currentPage.value = response.page;
    totalPages.value = response.pages;
    totalCards.value = response.total;
    loadingCards.value = false;
}

async function changePage(page) {
    await loadCards(page);
}

onMounted(() => {
    loadSetCodes();
    loadCards();
});
</script>

<template>
    <div class="all-cards-container">
        <div class="cards-header">
            <h1>Toutes les cartes</h1>
            <div class="search-filters">
                <div class="select-wrapper">
                    <label for="setCode">Filtrer par Set</label>
                    <select id="setCode" v-model="selectedSetCode" @change="loadCards(1)">
                        <option value="">Tous les sets</option>
                        <option v-for="code in setCodes" :key="code" :value="code">
                            {{ code }}
                        </option>
                    </select>
                </div>
            </div>
        </div>

        <div class="card-list-container">
            <div v-if="loadingCards" class="loading-state">
                <div class="spinner" />
                <p>Chargement des cartes...</p>
            </div>

            <div v-else class="card-list">
                <div v-for="card in cards" :key="card.id" class="card-result">
                    <router-link :to="{ name: 'get-card', params: { uuid: card.uuid } }" class="card-link">
                        <span class="card-name">{{ card.name }}</span>
                        <span class="card-uuid">({{ card.uuid }})</span>
                    </router-link>
                </div>

                <div class="pagination">
                    <button type="button" class="pagination-btn" :disabled="currentPage === 1" @click="changePage(currentPage - 1)">
                        ← Précédent
                    </button>

                    <span class="page-info">
                        Page {{ currentPage }} sur {{ totalPages }}
                    </span>

                    <button type="button" class="pagination-btn" :disabled="currentPage === totalPages" @click="changePage(currentPage + 1)">
                        Suivant →
                    </button>
                </div>
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

.all-cards-container {
    font-family: "Inter", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto,
        Oxygen, Ubuntu, Cantarell, "Open Sans", "Helvetica Neue", sans-serif;
    max-width: 800px;
    margin: 0 auto;
    padding: 2rem 1rem;
}

.cards-header {
    text-align: center;
    margin-bottom: 2rem;
}

h1 {
    color: var(--primary-color);
    margin-bottom: 1.5rem;
    font-size: 2rem;
    font-weight: 700;
}

.search-filters {
    display: flex;
    justify-content: center;
    margin-bottom: 1.5rem;
}

.select-wrapper {
    width: 100%;
    max-width: 300px;
}

label {
    display: block;
    margin-bottom: 0.5rem;
    color: var(--secondary-color);
}

select {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid var(--border-color);
    border-radius: 8px;
    background-color: var(--card-background);
    font-size: 1rem;
    transition: all 0.3s ease;
}

select:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(74, 108, 247, 0.1);
}

.card-list-container {
    background-color: var(--card-background);
    border-radius: 12px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
    padding: 1.5rem;
}

.loading-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 2rem;
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

.card-list {
    display: grid;
    gap: 0.5rem;
}

.card-result {
    background-color: var(--background-color);
    border-radius: 8px;
    transition: all 0.3s ease;
}

.card-link {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
    text-decoration: none;
    color: var(--text-color);
    transition: background-color 0.3s ease;
}

.card-link:hover {
    background-color: rgba(74, 108, 247, 0.05);
}

.card-name {
    font-weight: 600;
}

.card-uuid {
    color: var(--secondary-color);
    font-size: 0.85rem;
}

.pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 1rem;
    margin-top: 1.5rem;
    padding-top: 1.5rem;
    border-top: 1px solid var(--border-color);
}

.pagination-btn {
    background-color: var(--primary-color);
    color: blueviolet;
    border: none;
    padding: 0.5rem 1rem;
    border-radius: 6px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.pagination-btn:disabled {
    background-color: var(--border-color);
    cursor: not-allowed;
}

.page-info {
    color: var(--secondary-color);
}

@media (max-width: 600px) {
    .all-cards-container {
        padding: 1rem 0.5rem;
    }

    .card-link {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }

    .pagination {
        flex-direction: column;
        gap: 1rem;
    }
}
</style>
