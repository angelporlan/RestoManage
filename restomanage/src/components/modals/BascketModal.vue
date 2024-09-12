<template>
    <transition name="slide">
        <div v-if="show" class="basket-dropdown">
            <div class="title">
                <h1>Tu pedido</h1>
                <img class="cancel" src="@/assets/icons/cancel.svg" @click="closeBascket">
            </div>
            <body>
                <div class="total-clear">
                    <p class="total">Cantidad <span>{{ getTotalItems }}</span></p>
                    <button @click="clearCesta" class="clear-bascket">Vaciar cesta</button>
                </div>
                <ul>
                    <li v-for="item in cesta" :key="item.codigo">
                        <img class="img-stock" :src="require('@/assets/dishes/' + item.foto)">
                        <div class="nombre-precio">
                            <h3>{{ item.nombre }}</h3>
                            <p>{{ item.precio }}€</p>
                        </div>
                        <div class="cantidad">
                            <button class="cantidad-menos" @click="removeOneFromCesta(item.id)" aria-label="Disminuir cantidad">-</button>
                            <p class="cantidad-item">{{ item.cantidad }}</p>
                            <button class="cantidad-mas" @click="addToCesta(item)" aria-label="Aumentar cantidad">+</button>
                        </div>
                    </li>
                </ul>
            </body>
            <div class="subtotal">
                <p>Subtotal</p>
                <p class="total-price">{{ getTotalPrice }}€</p>
            </div>
            <button v-if="getTotalItems < 1" class="finish-order" @click="closeBascket(); $router.push('/menu')">Ir al menú</button>
            <button v-if="isUserLogged && getTotalItems >= 1" class="finish-order" @click="goToPayment">Finalizar pedido</button>
            <button v-if="!isUserLogged && getTotalItems >=1" class="finish-order" @click="this.closeBascket(); this.$router.push('/login')">Regístrate y finaliza pedido</button>
        </div>
    </transition>
    <div class="background" v-if="show"></div>
</template>


<script>
export default {
    props: ['show'],
    emits: ['close'],
    data() {
        return {
        };
    },
    methods: {
        closeBascket() {
            this.$emit('close');
        },
        removeFromCesta(codigo) {
            this.$store.dispatch('cesta/removeFromCesta', codigo);
        },
        clearCesta() {
            this.$store.dispatch('cesta/clearCesta');
        },
        addToCesta(dish) {
            this.$store.dispatch('cesta/addToCesta', {
                id: dish.id,
                nombre: dish.nombre,
                cantidad: 1,
                precio: dish.precio,
                foto: dish.foto
            });
            
        },
        removeOneFromCesta(dishId) {
            this.$store.dispatch('cesta/removeOneFromCesta', dishId);
        },
        goToPayment() {
            this.closeBascket();
            this.$router.push('/payment');
        }
    },
    computed: {
        isUserLogged() {
            const user = this.$store.getters['user/getUser'];
            return user !== null;
        },
        cesta() {
            return this.$store.getters['cesta/cesta'];
        },
        getTotalItems() {
            return this.cesta.reduce((acc, item) => acc + item.cantidad, 0);
        },
        getTotalPrice() {
            return this.cesta.reduce((acc, item) => acc + item.cantidad * item.precio, 0).toFixed(2);
        }
    }
}
</script>

<style scoped>
.basket-dropdown {
    position: fixed;
    top: 0;
    right: 0;
    width: 100%;
    height: 100vh;
    background-color: #fff;
    z-index: 1000;
    overflow-y: auto;
}

.background {
    position: fixed;
    top: 0;
    right: 0;
    width: 100%;
    height: 100vh;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 999;
}


.slide-enter-active,
.slide-leave-active {
    transition: all .3s ease;
}

.slide-enter-from,
.slide-leave-to {
    transform: translateX(100%);
}

.slide-enter-to,
.slide-leave-from {
    transform: translateX(0);
}

.cancel {
    width: 24px;
    height: auto;
    margin-left: auto;
    cursor: pointer;
}

.title {
    width: 100%;
    display: flex;
    padding: 24px;
}

li {
    display: flex;
    justify-content: left;
    align-items: center;
    padding: 12px;
    border-bottom: 1px solid #ccc;

}

.img-stock {
    width: 100px;
    height: 100px;
    object-fit: cover;
    border-radius: 8px;
}

.nombre-precio {
    margin: 0 12px;

}

.cantidad {
    display: flex;
    align-items: center;
    border: 1px solid #ccc;
    border-radius: 4px;
    overflow: hidden;
    max-width: 120px;
    margin-left: auto;
}

.cantidad-menos, .cantidad-mas {
    width: 32px;
    height: 32px;
    border: none;
    background-color: #f1f1f1;
    cursor: pointer;
    font-size: 20px;
    font-weight: bold;
    display: flex;
    justify-content: center;
    align-items: center;
    transition: background-color 0.3s ease;
}

.cantidad-menos:hover, .cantidad-mas:hover {
    background-color: #e1e1e1;
}

.cantidad-item {
    width: 40px;
    text-align: center;
    font-size: 16px;
    padding: 4px 0;
}


.total {
    padding: 12px;
}	

span {
    margin-left: 2px;
    border: 1px solid black;
    padding: 2px 12px;
    border-radius: 26px;
}

.finish-order {
    width: calc(100% - 24px);
    height: 48px;
    background-color: #FF6B6B;
    color: #fff;
    border: none;
    cursor: pointer;
    font-size: 18px;
    font-weight: 600;
    border-radius: 8px;
    margin: 12px;
}

.finish-order:hover {
    background-color: #FF4B4B;
}

.total-clear {
    display: flex;
    justify-content: space-between;
    align-items: center;

    border-bottom: 1px solid #ccc;
}

.subtotal {
    display: flex;
    justify-content: space-between;
    padding: 12px;
    border-top: 1px solid #ccc;
}

.total-price {
    font-weight: 600;
    margin-left: auto;
    font-size: 24px;
}

.clear-bascket {
    color: black;
    background-color: #fff;
    border: none;
    border-bottom: 2px solid gray;
    cursor: pointer;
    padding: 0 4px;
    margin-right: 12px;
}

@media (min-width: 768px) {
    .basket-dropdown {
        width: 50%;
        height: 100vh;
        background-color: #fff;
    }
}
</style>