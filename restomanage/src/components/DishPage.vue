<template>
  <MessageModal class="message" v-if="showMessageModal" :message="message" :color="color" />
  <div class="template">
    <p class="title" @click="goBack">
      <img src="@/assets/icons/arrow-left.svg" class="arrow-left"> Atrás
    </p>

    <body class="content" v-if="dish.nombre">
      <img class="dish-img" :src="require('@/assets/dishes/' + dish.foto)" alt="Imagen del plato">
      <div class="body-content">
        <div class="section1">
          <h1 class="nombre">{{ dish.nombre }} <span class="peso">({{ dish.peso }})</span></h1>
          <p class="descripcion">{{ dish.descripcion }}</p>
          <div class="nutri">
            <div class="calorias">
              <p>{{ dish.infoNutri.calorias }}</p>
              <p class="label" data-label="Calorías">Calorías</p>
            </div>
            <div class="proteinas">
              <p>{{ dish.infoNutri.proteina }}</p>
              <p class="label" data-label="Proteínas">Proteínas</p>
            </div>
            <div class="carbohidratos">
              <p>{{ dish.infoNutri.hidratos }}</p>
              <p class="label" data-label="Hidratos">Hidratos</p>
            </div>
          </div>
        </div>
        <div class="section2">
          <div class="cantidad">
            <p>Elige la cantidad</p>
            <div class="input">
              <button class="decrement" @click="decrement">-</button>
              <input class="cantidad-input" type="number" :value="value" readonly>
              <button class="increment" @click="increment">+</button>
            </div>
          </div>
          <button class="comprar" @click="addToCesta(dish, value)">Comprar</button>
          <Accordion class="accordion" :contents="[dish.ingredientes, dish.infoNutri, dish.uso]" />
        </div>
      </div>
    </body>
    <div v-else>
      <div class="loader-box">
        <img class="loader" src="@/assets/loader/loader.svg">
      </div>
    </div>
  </div>
</template>

<script>
import Accordion from '@/components/MenuPage/Acordeon.vue';
import dishService from '@/services/DishService';
import MessageModal from './modals/MessageModal.vue';

export default {
  name: 'DishPageComponent',
  components: {
    Accordion,
    MessageModal
  },
  data() {
    return {
      showMessageModal: false,
      message: '',
      color: '',
      value: 1,
      dish: {
        nombre: "",
        peso: "",
        descripcion: "",
        infoNutri: {
          calorias: "",
          proteina: "",
          hidratos: ""
        },
        ingredientes: "",
        uso: [],
        foto: ""
      }
    };
  },
  created() {
    this.getDish();
  },
  methods: {
    getDish() {
      dishService.getDishById(this.$route.params.id)
        .then(response => {
          this.dish = response;
        })

    },
    increment() {
      this.value++;
    },
    decrement() {
      if (this.value > 1) {
        this.value--;
      }
    },
    goBack() {
      window.history.back();
    },
    addToCesta(dish, value) {
      this.$store.dispatch('cesta/addToCesta', {
        id: dish.id,
        nombre: dish.nombre,
        cantidad: value,
        precio: dish.precio,
        foto: dish.foto
      });
      this.message = 'Producto añadido a la cesta';
      this.color = 'green';
      this.showMessageModal = true;
      setTimeout(() => {
        this.showMessageModal = false;
      }, 4000);
    }
  }
};
</script>

<style scoped>
input[type="number"]::-webkit-inner-spin-button,
input[type="number"]::-webkit-outer-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

.message {
  position: fixed;
  top: 12px;
  left: calc(50% - 125px);
}

.template {
  position: relative;
  padding: 24px;
  background-color: #f9f9f9;
}

.title {
  display: flex;
  align-items: center;
  font-size: 18px;
  font-weight: 600;
  color: #333;
  cursor: pointer;
  margin-bottom: 24px;
}

.title .arrow-left {
  width: 24px;
  height: auto;
  margin-right: 12px;
}

.loader-box {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
  background-color: rgba(255, 255, 255, 0.8);
}

.loader {
  width: 150px;
  height: 150px;
  animation: spin 2s linear infinite;
}

body {
  display: flex;
  flex-direction: column;
  align-items: center;
  width: 100%;
  margin: 0 auto;
  padding: 24px 16px;
  background-color: #fff;
  border-radius: 8px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.body-content {
  width: 100%;
  margin-top: 24px;
}

.dish-img {
  width: 100%;
  max-height: 300px;
  object-fit: cover;
  border-radius: 8px;
  margin-bottom: 24px;
}

.section1,
.section2 {
  width: 100%;
  padding: 24px;
  background-color: #fff;
  border-radius: 8px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  margin-bottom: 24px;
}

.section1 {
  display: flex;
  flex-direction: column;
}

.nombre {
  font-size: 24px;
  font-weight: 700;
  color: #333;
}

.peso {
  font-size: 16px;
  color: #666;
}

.descripcion {
  font-size: 16px;
  color: #555;
  margin-top: 12px;
}

.nutri {
  display: flex;
  justify-content: space-around;
  margin-top: 24px;
  background-color: #f1f1f1;
  border-radius: 8px;
  padding: 12px;
}

.calorias,
.proteinas,
.carbohidratos {
  display: flex;
  flex-direction: column;
  align-items: center;
}

.calorias p,
.proteinas p,
.carbohidratos p {
  margin: 4px 0;
}

.section2 .cantidad {
  margin-bottom: 24px;
}

.section2 .cantidad p {
  font-size: 16px;
  font-weight: 600;
  color: #333;
}

.input {
  display: flex;
  align-items: center;
  margin-top: 12px;
}

.cantidad-input {
  width: 50px;
  height: 32px;
  text-align: center;
  border: 1px solid #ccc;
  border-radius: 4px;
  margin: 0 12px;
}

.increment,
.decrement {
  width: 32px;
  height: 32px;
  border: 1px solid #ccc;
  background-color: #fff;
  border-radius: 4px;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 18px;
  color: #333;
}

.increment:hover,
.decrement:hover {
  background-color: #f1f1f1;
}

.comprar {
  width: 100%;
  height: 42px;
  background-color: #f7a541;
  color: white;
  font-size: 18px;
  font-weight: 700;
  border: none;
  border-radius: 8px;
  margin-top: 24px;
  cursor: pointer;
  transition: background-color 0.3s;
}

.comprar:hover {
  background-color: #e69530;
}

.accordion {
  margin-top: 32px;
}

.label {
  font-size: 0;
}

.label::before {
  content: attr(data-label);
  font-size: initial;
}

@media (max-width: 400px) {
  .label[data-label="Calorías"]::before {
    content: "Cal.";
  }

  .label[data-label="Proteínas"]::before {
    content: "Prot.";
  }

  .label[data-label="Hidratos"]::before {
    content: "Hidr.";
  }
}

@media (min-width: 900px) {
  body {
    flex-direction: row;
    align-items: flex-start;
    margin: 0;
  }

  .dish-img {
    max-width: 500px;
    max-height: 100%;
    margin-right: 24px;
    margin: 24px 0 0 0;
  }

  .body-content {
    margin-left: 24px;
  }

  .section1 {
    margin-top: 0;
  }
}

@keyframes spin {
  0% {
    transform: rotate(0deg);
  }

  100% {
    transform: rotate(360deg);
  }
}
</style>