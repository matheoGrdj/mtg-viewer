<script setup>
import { onMounted, ref, computed } from 'vue';
import { searchCards, fetchSetCodes } from '../services/cardService';

const cards = ref([]);
const loadingCards = ref(true);
const searchName = ref('');
const selectedSetCode = ref('');
const setCodes = ref([]);
let timeout = null;

async function loadSetCodes() {
    setCodes.value = await fetchSetCodes();
}

async function loadCards() {
    loadingCards.value = true;
    cards.value = await searchCards(searchName.value, selectedSetCode.value);
    loadingCards.value = false;
}

function delayedLoadCards() {
    clearTimeout(timeout);
    timeout = setTimeout(loadCards, 600);
}

onMounted(() => {
    loadSetCodes();
    loadCards();
});

const noResultsFound = computed(() => !loadingCards.value && cards.value.length === 0);
</script>

<template>
    <div class="card-search-container">
        <h1>Rechercher une Carte</h1>

        <div class="search-filters">
            <div>
                <label for="searchName">Nom de la carte</label>
                <input type="text" id="searchName" v-model="searchName" @input="delayedLoadCards" />
            </div>
            <div>
                <label for="setCode">Filtre par setcodes</label>
                <select id="setCode" v-model="selectedSetCode" @change="loadCards">
                    <option value="">Tous les setCodes</option>
                    <option v-for="code in setCodes" :key="code" :value="code">
                        {{ code }}
                    </option>
                </select>
            </div>
        </div>

        <div class="card-list">
            <div v-if="loadingCards" class="loading-state">
                Chargement... (la recherche ne fonctionne pas avec moins de 3 caractères)
            </div>

            <div v-else-if="noResultsFound" class="no-results-state">
                <div class="no-results-icon">🔍</div>
                <p>Aucune carte trouvée</p>
                <p class="no-results-hint">
                    Essayez d'ajuster votre recherche (3 charactères minimum) ou de sélectionner un autre set
                </p>
            </div>

            <div v-else>
                <div class="card" v-for="card in cards" :key="card.id">
                    <router-link :to="{ name: 'get-card', params: { uuid: card.uuid } }">
                        {{ card.name }} - {{ card.uuid }}
                    </router-link>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.card-search-container {
    max-width: 800px;
    margin: 0 auto;
    padding: 1rem;
}

.search-filters {
    display: flex;
    gap: 1rem;
    margin-bottom: 1.5rem;
    background-color: white;
    padding: 1.5rem;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.search-filters>div {
    flex: 1;
    display: flex;
    flex-direction: column;
}

label {
    margin-bottom: 0.5rem;
    font-weight: 600;
    color: #555;
}

input,
select {
    padding: 0.75rem;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 1rem;
    transition: all 0.3s ease;
}

input:focus,
select:focus {
    outline: none;
    border-color: #4a90e2;
    box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.1);
}

h1 {
    text-align: center;
    color: #2c3e50;
    margin-bottom: 1.5rem;
    font-weight: 700;
}

.card-list {
    background-color: white;
    border-radius: 8px;
    padding: 1.5rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    min-height: 200px;
}

.card {
    padding: 0.75rem;
    border-bottom: 1px solid #eee;
    transition: background-color 0.3s ease;
}

.card:last-child {
    border-bottom: none;
}

.card:hover {
    background-color: #f9f9f9;
}

.card a {
    color: #4a90e2;
    text-decoration: none;
    font-weight: 500;
    transition: color 0.3s ease;
}

.card a:hover {
    color: #2c3e50;
    text-decoration: underline;
}

.loading-state,
.no-results-state {
    text-align: center;
    color: #888;
    padding: 1.5rem;
    font-style: italic;
}

.no-results-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

.no-results-icon {
    font-size: 4rem;
    margin-bottom: 1rem;
    opacity: 0.6;
}

.no-results-hint {
    color: #666;
    font-size: 0.9rem;
    margin-top: 0.5rem;
}
</style>
