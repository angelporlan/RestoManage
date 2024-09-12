import axios from 'axios';

class UserService {
    constructor(baseURL) {
        this.baseURL = baseURL;
    }

    async getUsers() {
        try {
            const response = await axios.get(`${this.baseURL}/users`, {
                headers: {
                    'Content-Type': 'application/ld+json'
                }
            });
            return response.data;
        } catch (error) {
            throw new Error(`Error fetching users: ${error.message}`);
        }
    }

    async getUserById(id) {
        try {
            const response = await axios.get(`${this.baseURL}/users/${id}`);
            return response.data;
        } catch (error) {
            throw new Error(`Error fetching user with id ${id}: ${error.message}`);
        }
    }

    async createUser(user) {  
        try {
            const response = await axios.post(`${this.baseURL}/users`, user, {
                headers: {
                    'Content-Type': 'application/ld+json'
                }
            });
            return response.data;
        } catch (error) {
            throw new Error(`Error creating user: ${error.message}`);
        }
    }

    async updateUser(id, user) {
        try {
            const response = await axios.put(`${this.baseURL}/users/${id}`, user);
            return response.data;
        } catch (error) {
            throw new Error(`Error updating user with id ${id}: ${error.message}`);
        }
    }

    async patchUser(id, user) {
        try {
            const response = await axios.patch(`${this.baseURL}/users/${id}`, user);
            return response.data;
        } catch (error) {
            throw new Error(`Error patching user with id ${id}: ${error.message}`);
        }
    }

    async deleteUser(id) {
        try {
            const response = await axios.delete(`${this.baseURL}/users/${id}`);
            return response.status === 200;
        } catch (error) {
            throw new Error(`Error deleting user with id ${id}: ${error.message}`);
        }
    }

    async getUserByEmailAndPassword(email, password) {
        try {
            const response = await axios.get(`${this.baseURL}/api/users`, {
                params: {
                    email: email,
                    password: password
                }
            });
            const users = response.data['hydra:member'];
            for (const user of users) {
                if (user.email === email && user.password === password) {
                    return user;
                }
            }
            return null;

        } catch (error) {
            throw new Error(`Error fetching user by email and password: ${error.message}`);
        }
    }

    async checkEmailIsUnique(email) {
        try {
            const response = await axios.get(`${this.baseURL}/api/users`, {
                params: {
                    email: email,
                }
            });
            const users = response.data['hydra:member'];
            for (const user of users) {
                if (user.email === email) {
                    return true;
                }
            }
            return false;

        } catch (error) {
            throw new Error(`Error fetching user by email and password: ${error.message}`);
        }
    
    }
}

const userService = new UserService('http://127.0.0.1:8000');

export default userService;
