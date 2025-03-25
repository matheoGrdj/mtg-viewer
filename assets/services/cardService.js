/* eslint-disable no-console */
export async function fetchAllCards(page, setCode = null) {
    console.log('Fetching all cards... for page', page, setCode);
    const startTime = performance.now();
    try {
        const params = new URLSearchParams({ page });
        if (setCode) {
            params.append('setCode', setCode);
        }
        const response = await fetch(`/api/card/all?${params}`);
        if (!response.ok) throw new Error('Failed to fetch cards');
        const result = await response.json();
        const duration = performance.now() - startTime;
        console.log(
            `Successfully fetched ${result.length} cards in ${duration.toFixed(2)}ms`,
        );
        return result;
    } catch (error) {
        console.error('Error fetching all cards:', error);
        throw error;
    }
}

export async function fetchCard(uuid) {
    console.log(`Fetching card ${uuid}...`);
    const startTime = performance.now();
    try {
        const response = await fetch(`/api/card/${uuid}`);
        if (response.status === 404) {
            console.warn(`Card ${uuid} not found`);
            return null;
        }
        if (!response.ok) throw new Error('Failed to fetch card');
        const card = await response.json();
        card.text = card.text.replaceAll('\\n', '\n');
        const duration = performance.now() - startTime;
        console.log(
            `Successfully fetched card ${uuid} in ${duration.toFixed(2)}ms`,
        );
        return card;
    } catch (error) {
        console.error(`Error fetching card ${uuid}:`, error);
        throw error;
    }
}

export async function searchCards(name, setCode = null) {
    console.log(`Searching cards with name ${name} and setCode ${setCode}`);
    try {
        const params = new URLSearchParams({ name });
        if (setCode) {
            params.append('setCode', setCode);
        }
        const response = await fetch(`/api/card/search?${params}`);
        // const response = await fetch('/api/card/search', {
        //     method: 'POST',
        //     headers: {
        //         'Content-Type': 'application/json',
        //     },
        //     body: JSON.stringify({ name }),
        // });
        if (!response.ok) throw new Error('Failed to search cards');
        const result = await response.json();
        console.log(`Successfully fetched ${result.length} cards`);
        return result;
    } catch (error) {
        console.error('Error searching cards:', error);
        throw error;
    }
}

export async function fetchSetCodes() {
    try {
        const response = await fetch('/api/card/setcodes');
        if (!response.ok) throw new Error('Failed to fetch set codes');
        return await response.json();
    } catch (error) {
        console.error('Error fetching set codes:', error);
        throw error;
    }
}
