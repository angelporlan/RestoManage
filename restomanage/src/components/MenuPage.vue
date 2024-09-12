<template>

    <div class="template">
        <div class="buttons">
            <button 
                class="filter-button" 
                :class="{ 'filter-button-selected': filterButton === 'Todos' }" 
                @click="setFilter('Todos')">Todos
            </button>
            <button 
                class="filter-button" 
                :class="{ 'filter-button-selected': filterButton === 'Entrantes' }" 
                @click="setFilter('Entrantes')">Entrantes
            </button>
            <button 
                class="filter-button" 
                :class="{ 'filter-button-selected': filterButton === 'Principales' }" 
                @click="setFilter('Principales')">Principales
            </button>
            <button 
                class="filter-button" 
                :class="{ 'filter-button-selected': filterButton === 'Postres' }" 
                @click="setFilter('Postres')">Postres
            </button>
        </div>

        <hr>

        <div class="container-2">
            <h2 class="h-container-2">{{ filterButton }} <span>({{numberOfFilter}})</span></h2>
            <p class="p-container-2">{{ filterDescription }}</p>
        </div>

        <div class="container-4">
            <DishCard v-for="dish in actualMenu" :key="dish.id" :dish="dish" @addToCesta="notificationModal" />
        </div>

        <div v-if="!isLoaded" class="loader-box">
            <img class="loader" src="@/assets/loader/loader.svg">
        </div>
        <MessageModal v-if="messageModal" class="message" message="Plato añadido a la cesta" color="green"/>
    </div>

</template>

<script>
import DishCard from '@/components/MenuPage/DishCard.vue';
import dishService from '@/services/DishService';
import MessageModal from './modals/MessageModal.vue';

export default {
    components: {
        DishCard,
        MessageModal
    },
    data() {
        return {
            messageModal: false,
            filterButton: 'Todos',
            principales: [],
            entrantes: [],
            postres: [],
            actualMenu: [],
            filterDescriptions: {
                'Todos': 'Elige de nuestra gran variedad de platos únicos, llene su cesta y haga su pedido ahora.',
                'Entrantes': 'Descubre nuestros deliciosos entrantes para abrir tu apetito.',
                'Principales': 'Disfruta de nuestros platos principales preparados con los mejores ingredientes.',
                'Postres': 'Termina tu comida con nuestros irresistibles postres.'
            },
            menu: [],
            isLoaded: false
        }

    },
    created() {
        this.getDishes();
    },
    methods: {
        getDishes() {
            dishService.getDishes()
          .then(response => {
            this.menu = response['hydra:member'];
            this.categorizeMenu();
            this.actualMenu = this.menu;
            this.isLoaded = true;
          })

        },
        categorizeMenu() {
            this.menu.forEach(dish => {
                if (dish.categoria === 'Principales') {
                    this.principales.push(dish);
                } else if (dish.categoria === 'Entrantes') {
                    this.entrantes.push(dish);
                } else if (dish.categoria === 'Postres') {
                    this.postres.push(dish);
                }
            });
        },
        setFilter(filter) {
            this.filterButton = filter;
            if (filter === 'Todos') {
                this.actualMenu = this.menu;
            } else if (filter === 'Entrantes') {
                this.actualMenu = this.entrantes;
            } else if (filter === 'Principales') {
                this.actualMenu = this.principales;
            } else {
                this.actualMenu = this.postres;
            }
        },
        notificationModal() {
            this.messageModal = true;
            setTimeout(() => {
                this.messageModal = false;
            }, 4000);
        }
    },
    computed: {
        numberOfFilter() {
            if (this.filterButton === 'Todos') {
                return this.menu.length;
            } else if (this.filterButton === 'Entrantes') {
                return this.entrantes.length;
            } else if (this.filterButton === 'Principales') {
                return this.principales.length;
            } else {
                return this.postres.length;
            }
        },
        filterDescription() {
            return this.filterDescriptions[this.filterButton];
        }
    }
}

</script>

<style scoped>
.message {
    position: fixed;
    top: 12px;
    left: calc(50% - 125px);
}

.template {
    margin: 12px;
    position: relative;
}

.loader-box {
    position: absolute;
    width: 100%;
    height: 100vh;
}

.loader {
    width: 150px;
    height: 150px;
    margin-left: calc(50% - 75px);
    margin-top: 200px;
    animation: spin 2s linear infinite;
}

.buttons {
    display: flex;
    justify-content: left;
    margin: 24px 0;
    height: 42px;
}

.filter-button {
    background-color: white;
    color: black;
    border: 1px solid black;
    border-radius: 18px;
    padding: 8px 20px;
    font-size: 16px;
    font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
    margin-right: 12px;
    cursor: pointer;
}

.filter-button-selected {
    background-color: black;
    color: white;
}

.container-2 {
    background-color: rgb(238, 237, 237);
    padding: 24px;
}

.h-container-2 {
    font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
    font-size: 24px;
    font-weight: 600;
}

.p-container-2 {
    font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
    font-size: 12px;
    font-weight: 400;
    margin-top: 24px;
}

hr {
    margin-bottom: 24px;
}

.container-4 {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 24px;
    margin-top: 24px;
}


@media (max-width: 822px) {
    .buttons {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        margin-bottom: 68px;
    }
}

@media (min-width: 1200px) {
    .template {
        margin-left: 24px;
        margin-right: 24px;
    }
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>