import axios from 'axios';

const API_BASE_URL = 'http://localhost:8000';

async function getRewards() {
    try {
        const response = await axios.get(`${API_BASE_URL}/api/rewards`, {
            headers: {
                'Content-Type': 'application/ld+json'
            }
        });
        return response.data['hydra:member'];
    } catch (error) {
        throw new Error(`Error fetching users: ${error.message}`);
    }
}

async function createReward(value) {
    const response = await fetch(`${API_BASE_URL}/reward`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ value }),
    });

    if (!response.ok) {
        const errorData = await response.json();
        throw new Error(errorData.error || 'Error creating reward');
    }

    return response.json();
}


async function deleteReward(code) {
    const response = await fetch(`${API_BASE_URL}/reward/${code}`, {
        method: 'DELETE',
    });

    if (!response.ok) {
        const errorData = await response.json();
        throw new Error(errorData.error || 'Error deleting reward');
    }

    return response.json();
}

export { createReward, deleteReward, getRewards };
