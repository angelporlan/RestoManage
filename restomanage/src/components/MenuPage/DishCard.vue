<template>

    <div class="card" @click="goToDish">
        <img :src="require('@/assets/dishes/' + dish.foto)" alt="Imagen del plato">
        <div class="card-body">
            <h5 class="card-title">{{ dish.nombre }}</h5>
            <div class="peso-precio">
                <p>({{ dish.peso }})</p>
                <p>{{ dish.precio }} €</p>
            </div>
            <div class="nutri">
                <div class="calorias">
                    <p>{{ dish.infoNutri.calorias }}</p>
                    <p>Calorias</p>
                </div>
                <div class="proteinas">
                    <p>{{ dish.infoNutri.proteina }}</p>
                    <p>Proteinas</p>
                </div>
                <div class="carbohidratos">
                    <p>{{ dish.infoNutri.hidratos }}</p>
                    <p>Hidratos</p>
                </div>
            </div>
            <button @click.stop="addToCesta(dish)">Añadir</button>
        </div>
    </div>

</template>

<script>

export default {
    name: 'DishCardComponent',
    emits: ['addToCesta'],
    props: {
        dish: {
            type: Object,
            required: true,
        },
    },
    data() {
        return {

        };
    },
    methods: {
        goToDish() {
            this.$router.push(`/dish/${this.dish.id}`);
        },
        addToCesta(dish) {
            this.$emit('addToCesta');
            this.$store.dispatch('cesta/addToCesta', {
                id: dish.id,
                nombre: dish.nombre,
                cantidad: 1,
                precio: dish.precio,
                foto: dish.foto
            });
        }
    },
}
</script>

<style scoped>
.card {
    width: auto;
    display: inline-block;
    background-color: rgb(241, 237, 237);
    border-radius: 8px;
    cursor: pointer;
}

img {
    width: 100%;
    height: 210px;
    object-fit: cover;
    border-radius: 8px 8px 0 0;
}

.card-body {
    padding: 18px;
}

.nutri {
    display: flex;
    justify-content: space-between;
    margin-top: 12px;
    background-color: white;
    border-radius: 8px;
    padding: 6px;
}

.calorias,
.proteinas,
.carbohidratos {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.peso-precio {
    margin-top: 12px;
    display: flex;
    justify-content: space-between;
}

button {
    width: 100%;
    height: 32px;
    border-radius: 8px;
    background-color: #FF6B6B;
    color: white;
    border: none;
    cursor: pointer;
    margin-top: 24px;
}

button:hover {
    background-color: #FF4D4D;
}
</style>