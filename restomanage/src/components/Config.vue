<template>
    <div class="template">
        <div class="section dishes">
            <div class="buttons">
                <button @click="showAddDishModal = true">Añadir plato</button>
                <button class="delete" v-if="selectedDishIds.length > 0" @click="deleteDishes">Eliminar plato</button>
            </div>
            <div class="dishes-list">
                <div class="title">
                    <h2>Platos
                        <hr><span class="h2-span" v-if="dishes">{{ dishes.length }} En total</span>
                    </h2>
                    <div class="paginate-buttons">
                        <button @click="currentPage = currentPage - 1" :disabled="currentPage === 1">Anterior</button>
                        <span>{{ currentPage }} de {{ Math.ceil(dishes.length / itemsPerPage) }}</span>
                        <button @click="currentPage = currentPage + 1"
                            :disabled="paginatedDishes.length < itemsPerPage">Siguiente</button>
                    </div>
                </div>
                <img v-if="loadingDishes" class="loader" src="@/assets/loader/loader.svg" alt="Cargando platos">
                <div v-if="!loadingDishes">
                    <div class="dish-div" v-for="dish in paginatedDishes" :key="dish.id">
                        <div class="dish-info">
                            <input class="checkbox" type="checkbox" :id="'checkbox-' + dish.id"
                                @change="toggleSelection(dish.id)">
                            <img class="dish-img" :src="require('@/assets/dishes/' + dish.foto)" alt="Imagen del plato">
                            <div class="dish-details">
                                <p class="dish-name">{{ dish.nombre }}</p>
                                <p class="dish-price">{{ dish.precio }} €</p>
                            </div>
                        </div>
                        <button class="dish-button" @click="editDish(dish)">Editar</button>
                    </div>
                </div>

            </div>
        </div>
        <div class="section users">
            <div class="buttons">
                <button class="delete" v-if="selectedUserIds.length > 0" @click="deleteUser">Eliminar usuario</button>
                <button v-if="selectedUserIds.length > 0" @click="setAdmin">Ascender a administrador</button>
            </div>
            <div class="title">

                <h2>Usuarios
                    <hr><span class="h2-span" v-if="users">{{ users.length }} En total</span>
                </h2>
                <div class="paginate-buttons">
                    <button @click="currentPageUsers = currentPageUsers - 1"
                        :disabled="currentPageUsers === 1">Anterior</button>
                    <span>{{ currentPageUsers }} de {{ Math.ceil(users.length / itemsPerPageUsers) }}</span>
                    <button @click="currentPageUsers = currentPageUsers + 1"
                        :disabled="paginatedUsers.length < itemsPerPageUsers">Siguiente</button>
                </div>
            </div>

            <img v-if="loadingUsers" class="loader" src="@/assets/loader/loader.svg" alt="Cargando usuarios">
            <div v-if="!loadingUsers">
                <div class="user-div" v-for="user in paginatedUsers" :key="user.id">
                    <div class="checkbox-email">
                        <input class="checkbox" type="checkbox" :id="'checkbox-' + user.id"
                            v-if="user.id != $store.getters['user/getUser'].id" @change="toggleUserSelection(user.id)">
                        <p>{{ user.email }}</p>
                    </div>
                    <p>{{ user.roles[0] === 'ROLE_USER' ? 'usuario' : 'admin' }}</p>
                </div>
            </div>

        </div>
        <DishModal class="dishmodal" v-if="showDishModal" :dish="selectedDish" @close="showDishModal = false"
            @save="saveDish" />
        <AddDishModal class="adddishmodal" v-if="showAddDishModal" @close="showAddDishModal = false"
            @save="addDishToBBDD" />
        <MessageModal v-if="messageModal" class="message" :message="message" color="green" />
    </div>
</template>

<script>
import dishService from '@/services/DishService';
import userService from '@/services/UserService';
import DishModal from '@/components/modals/DishModal.vue';
import AddDishModal from '@/components/modals/AddDish.vue';
import MessageModal from './modals/MessageModal.vue';

export default {
    name: 'ConfigComponent',
    components: {
        DishModal,
        AddDishModal,
        MessageModal
    },
    data() {
        return {
            dishes: [],
            users: [],
            showDishModal: false,
            showAddDishModal: false,
            selectedDish: null,
            selectedDishIds: [],
            selectedUserIds: [],
            currentPage: 1,
            itemsPerPage: 5,
            currentPageUsers: 1,
            itemsPerPageUsers: 7,
            loadingDishes: false,
            loadingUsers: false,
            messageModal: false,
            message: '',
            color: ''
        };
    },
    created() {
        this.isAdmin();
        this.getDishes();
        this.getUsers();
    },
    methods: {
        isAdmin() {

            const user = this.$store.getters['user/getUser'];


            if (user.roles[0] !== 'ROLE_ADMIN') {
                this.$router.push('/');
            }
        },
        setAdmin() {
            this.selectedUserIds.forEach(userId => {
                const user = this.users.find(u => u.id === userId);
                user.roles = ['ROLE_ADMIN'];
                userService.updateUser(userId, user)
                    .then(() => {
                        this.getUsers();
                    })
                    .catch(e => {
                        console.error('Error al ascender a administrador:', e);
                    });
                this.selectedUserIds = [];

            });
            this.selectedUserIds = [];
        },
        deleteUser() {
            this.selectedUserIds.forEach(userId => {
                userService.deleteUser(userId)
                    .then(() => {
                        this.getUsers();
                    })
                    .catch(e => {
                        console.error('Error al eliminar usuario:', e);
                    });
            });
            this.selectedUserIds = [];
        },
        toggleUserSelection(userId) {
            const index = this.selectedUserIds.indexOf(userId);
            if (index === -1) {
                this.selectedUserIds.push(userId);
            } else {
                this.selectedUserIds.splice(index, 1);
            }
        },
        addDishToBBDD(dish) {
            this.loadingDishes = true;
            dishService.createDish(dish)
            this.getDishes();
            this.showAddDishModal = false;
        },
        deleteDishes() {
            this.selectedDishIds.forEach(dishId => {
                this.loadingDishes = true;
                dishService.deleteDish(dishId)
                    .then(() => {
                        this.getDishes();
                    })
                    .catch(e => {
                        console.error('Error al eliminar plato:', e);
                    })
                    .finally(() => {
                        this.loadingDishes = false;
                    });
            });
            this.selectedDishIds = [];
        },
        toggleSelection(dishId) {
            const index = this.selectedDishIds.indexOf(dishId);
            if (index === -1) {
                this.selectedDishIds.push(dishId);
            } else {
                this.selectedDishIds.splice(index, 1);
            }
        },
        getDishes() {
            this.loadingDishes = true;
            dishService.getDishes()
                .then(response => {
                    this.dishes = response['hydra:member'];
                })
                .catch(e => {
                    console.log(e);
                })
                .finally(() => {
                    this.loadingDishes = false;
                });
        },
        async getUsers() {
            this.loadingUsers = true;
            this.users = await userService.getUsers()
                .catch(e => {
                    console.error('Error al obtener usuarios:', e);
                })
                .finally(() => {
                    this.loadingUsers = false;
                });


        },
        editDish(dish) {
            this.selectedDish = dish;
            this.showDishModal = true;
        },


        saveDish(dish, isNewImage) {
            if (isNewImage && dish.foto.startsWith('data:image')) {
                dish.foto = dish.foto.split(',')[1];
            }

            if (isNewImage) {
                this.loadingDishes = true;
                dishService.createDish(dish)
            } else {
                dishService.updateDish(dish.id, dish)
            }

            const index = this.dishes.findIndex(d => d.id === dish.id);
            if (index !== -1) {
                this.dishes[index] = dish;
            } else {
                this.dishes.push(dish);
            }

            this.messageModal = true;
            this.message = 'Plato guardado correctamente';
            this.color = 'green';
            setTimeout(() => {
                this.messageModal = false;
            }, 4000);

        }

    },
    computed: {
        paginatedDishes() {
            const startIndex = (this.currentPage - 1) * this.itemsPerPage;
            return this.dishes.slice(startIndex, startIndex + this.itemsPerPage);
        },
        paginatedUsers() {
            const startIndex = (this.currentPageUsers - 1) * this.itemsPerPageUsers;
            return this.users.slice(startIndex, startIndex + this.itemsPerPageUsers);
        }
    }
};
</script>
<style scoped>
.message {
    position: fixed;
    top: 12px;
    left: calc(50% - 125px);
}

.dishmodal,
.adddishmodal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100vh;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 1000;
    display: flex;
    justify-content: center;
    align-items: center;
}

.template {
    display: flex;
    flex-direction: column;
    padding: 24px;
    background-color: #f7f9fc;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.section {
    background-color: #ffffff;
    border-radius: 8px;
    margin-bottom: 24px;
    padding: 24px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    min-height: 658px;
}


.buttons {
    display: flex;
    justify-content: flex-end;
    margin-bottom: 16px;
    min-height: 33.6px;
}

.buttons button {
    background-color: #0272b3;
    color: #ffffff;
    border: none;
    padding: 8px 16px;
    border-radius: 4px;
    cursor: pointer;
    margin-left: 8px;
    transition: background-color 0.3s;
}

.buttons button:hover {
    background-color: #025a91;
}

.dishes-list {
    width: 100%;
}

.dishes-list h2 {
    margin-bottom: 16px;
    color: #333333;
}

.dish-div {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 16px;
    padding: 16px;
    background-color: #f1f1f1;
    border-radius: 8px;
    width: 100%;
    transition: background-color 0.3s;
}

.dish-div:hover {
    background-color: #e0e0e0;
}

.dish-info {
    display: flex;
    align-items: center;
}

.dish-img {
    width: 50px;
    height: 50px;
    object-fit: cover;
    border-radius: 8px;
    margin-right: 16px;
}

.h2-span {
    color: #333333;
    font-size: 14px;

}

.checkbox-email {
    display: flex;
    align-items: center;
}

.checkbox {
    width: 20px;
    height: 20px;
    margin-right: 16px;
}

.dish-details {
    display: flex;
    flex-direction: column;
}

.dish-name,
.dish-price {
    margin: 0;
    color: #333333;
}

.dish-button {
    background-color: #0272b3;
    color: #ffffff;
    border: none;
    padding: 8px 16px;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.dish-button:hover {
    background-color: #025a91;
}

.users h2 {
    margin-bottom: 16px;
    color: #333333;
}

.user-div {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 16px;
    padding: 16px;
    background-color: #f1f1f1;
    border-radius: 8px;
    transition: background-color 0.3s;
}

.user-div:hover {
    background-color: #e0e0e0;
}

.user-div p {
    margin: 0;
    color: #333333;
}

.delete {
    background-color: #ff0000 !important;

}

.title {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;

}

.paginate-buttons button {
    background-color: #fff;
    color: #0272b3;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.paginate-buttons button:disabled {
    color: #ccc;
    cursor: not-allowed;
}

.paginate-buttons button:hover {
    background-color: #f1f1f1;
}




.paginate-buttons span {
    color: #333333;
    margin: 0 12px;
}

.paginate-buttons span {
    color: #333333;
}


h2 {
    margin: 0;
}

.loader {
    width: 50px;
    height: 50px;
    margin: 0 auto;
    display: block;
    animation: spin 1s linear infinite;
    margin: 48px auto;
}


@keyframes spin {
    0% {
        transform: rotate(0deg);
    }

    100% {
        transform: rotate(360deg);
    }
}



@media (min-width: 768px) {
    .template {
        flex-direction: row;
        gap: 24px;
    }

    .section {
        flex: 1;
    }
}

@media (min-width: 1200px) {
    .template {
        padding: 32px;
    }

    .section {
        padding: 32px;
    }
}
</style>
