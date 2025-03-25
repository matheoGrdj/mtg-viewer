<template>
    <div class="home-container">
        <div class="home-content">
            <h1>Home Page</h1>
            <nav class="home-nav">
                <ul>
                    <li>
                        <router-link :to="{ name: 'all-cards' }" class="nav-link">
                            <div class="link-icon">📇</div>
                            <span>Toutes les cartes</span>
                            <div class="link-arrow">→</div>
                        </router-link>
                    </li>
                    <li>
                        <router-link :to="{ name: 'search-cards' }" class="nav-link">
                            <div class="link-icon">🔍</div>
                            <span>Rechercher une carte</span>
                            <div class="link-arrow">→</div>
                        </router-link>
                    </li>
                </ul>
            </nav>
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

.home-container {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    background-color: var(--background-color);
    padding: 2rem;
}

.home-content {
    width: 100%;
    max-width: 500px;
    background-color: var(--card-background);
    border-radius: 16px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    padding: 2.5rem;
    text-align: center;
}

h1 {
    color: var(--primary-color);
    margin-bottom: 2rem;
    font-size: 2rem;
    font-weight: 700;
}

.home-nav ul {
    list-style-type: none;
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.nav-link {
    display: flex;
    align-items: center;
    justify-content: space-between;
    text-decoration: none;
    background-color: var(--background-color);
    color: var(--text-color);
    padding: 1rem 1.5rem;
    border-radius: 10px;
    transition: all 0.3s ease;
    font-weight: 500;
    border: 1px solid var(--border-color);
}

.nav-link:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 15px rgba(74, 108, 247, 0.1);
    border-color: var(--primary-color);
}

.link-icon {
    font-size: 1.5rem;
    margin-right: 1rem;
    opacity: 0.7;
}

.link-arrow {
    color: var(--secondary-color);
    font-weight: bold;
    transition: transform 0.3s ease;
}

.nav-link:hover .link-arrow {
    transform: translateX(5px);
}

.nav-link span {
    flex-grow: 1;
    text-align: left;
}

@media (max-width: 600px) {
    .home-content {
        width: 100%;
        padding: 1.5rem;
    }

    h1 {
        font-size: 1.5rem;
    }

    .nav-link {
        padding: 0.75rem 1rem;
    }
}
</style>
