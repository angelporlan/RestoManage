class DishService {
    constructor(baseURL) {
        this.baseURL = baseURL;
    }

    async getDishes() {
        const response = await fetch(`${this.baseURL}/api/dishes`);
        if (!response.ok) {
            throw new Error(`Error fetching dishes: ${response.statusText}`);
        }
        return response.json();
    }

    async getDishById(id) {
        const response = await fetch(`${this.baseURL}/api/dishes/${id}`);
        if (!response.ok) {
            throw new Error(`Error fetching dish with id ${id}: ${response.statusText}`);
        }
        return response.json();
    }

    async createDish(dish) {
        const response = await fetch(`${this.baseURL}/dishes`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(dish)
        });
        if (!response.ok) {
            throw new Error(`Error creating dish: ${response.statusText}`);
        }
        return response.json();
    }

    async updateDish(id, dish) {
        const response = await fetch(`${this.baseURL}/api/dishes/${id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/ld+json'
            },
            body: JSON.stringify(dish)
        });
        if (!response.ok) {
            throw new Error(`Error updating dish with id ${id}: ${response.statusText}`);
        }
        return response.json();
    }

    async patchDish(id, dish) {
        const response = await fetch(`${this.baseURL}/api/dishes/${id}`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(dish)
        });
        if (!response.ok) {
            throw new Error(`Error patching dish with id ${id}: ${response.statusText}`);
        }
        return response.json();
    }

    async deleteDish(id) {
        const response = await fetch(`${this.baseURL}/api/dishes/${id}`, {
            method: 'DELETE'
        });
        if (!response.ok) {
            throw new Error(`Error deleting dish with id ${id}: ${response.statusText}`);
        }
        return response.ok;
    }
}

const dishService = new DishService('http://127.0.0.1:8000');

export default dishService;