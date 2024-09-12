<template>
  <div class="container">
    <main class="main">
      <section class="intro">
        <div class="intro-container">
          <div class="intro-text">
            <h1 class="title">Platos deliciosos, entregados a su puerta</h1>
            <p class="description">Descubra un mundo de delicias con nuestra selección cuidadosamente
              seleccionada de alimentos gourmet. Deléitese con los mejores sabores, entregados directamente en la puerta
              de su casa.</p>
            <router-link to="/menu" class="cta">Ver menu</router-link>
          </div>
          <img src="@/assets/media/stock1.jpg" alt="Delicious Food" class="intro-image" />
        </div>
      </section>
      <section class="categories">
        <div class="categories-container">
          <div class="categories-header">
            <h2 class="categories-title">Explora nuestras categorías</h2>
            <p class="categories-description">Explore nuestra amplia selección de deliciosas categorías de alimentos y
              encuentre su próxima aventura culinaria.</p>
          </div>
          <div class="categories-grid">
            <router-link to="/menu" class="category-item">
              <img class="svg-categories" src="@/assets/icons/entrantes.svg" alt="Entrantes">
              <h3 class="category-name">Entrantes</h3>
            </router-link>
            <router-link to="/menu" class="category-item">
              <img class="svg-categories" src="@/assets/icons/principales.svg" alt="Principales">
              <h3 class="category-name">Principales</h3>
            </router-link>
            <router-link to="/menu" class="category-item">
              <img class="svg-categories" src="@/assets/icons/postres.svg" alt="Postres">
              <h3 class="category-name">Postres</h3>
            </router-link>
          </div>
        </div>
      </section>
      <section class="featured">
        <div class="featured-container">
          <div class="featured-header">
            <h2 class="featured-title">Productos Destacados</h2>
            <p class="featured-description">Descubra nuestra cuidada selección de los mejores alimentos gourmet,
              perfectos para cualquier ocasión.</p>
          </div>
          <div class="featured-grid" v-if="dishesLoaded">

            <div v-for="dish in menu" :key="dish.id" class="card">
              <img :src="require('@/assets/dishes/' + dish.foto)" alt="Imagen del plato" class="card-image" />
              <div class="card-content">
                <h3 class="card-title">{{ dish.nombre }}</h3>
                <p class="card-description">{{ dish.descripcion }}</p>
                <div class="card-footer">
                  <span class="card-price">{{ dish.precio }} €</span>
                  <router-link :to="`/dish/${dish.id}`" class="card-cta">Comprar</router-link>
                </div>
              </div>
            </div>


          </div>

          <div v-if="!dishesLoaded" class="div-loader">
            <img class="loader" src="@/assets/loader/loader.svg" />
          </div>


        </div>
      </section>
      <section class="mission">
        <div class="mission-container">
          <div class="mission-text">
            <div class="mission-header">Nuestra misión</div>
            <h2 class="mission-title">Ofrecer una amplia variedad de productos</h2>
            <p class="mission-description">En RestoManage, nuestra misión es brindarles a nuestros clientes los mejores
              alimentos gourmet, provenientes de los mejores productores de todo el mundo. Creemos que todos merecen
              experimentar el placer de unas delicias culinarias excepcionales, por eso nos dedicamos a entregar
              productos de la más alta calidad directamente a su puerta.</p>
          </div>
          <div class="values-text">
            <div class="values-header">Nuestros valores</div>
            <p class="values-description">Calidad, sostenibilidad y servicio excepcional son los valores fundamentales
              que impulsan todo lo que hacemos en Delicious Bites. Estamos comprometidos a obtener los mejores
              ingredientes, apoyar a los productores locales y garantizar que la experiencia de cada cliente sea nada
              menos que excepcional.</p>
          </div>
        </div>
      </section>
    </main>
  </div>
</template>

<script>
import dishService from '@/services/DishService';
export default {
  name: 'Component',
  data() {
    return {
      dishesLoaded: false,
      menu: []
    }
  },
  mounted() {
    this.getDishes();
  },
  methods: {
    getDishes() {
      dishService.getDishes()
        .then(response => {
          this.menu = response['hydra:member'];
          this.menu = this.menu.sort(() => Math.random() - 0.5).slice(0, 3);
          this.dishesLoaded = true;
        })
    }
  }
}
</script>

<style scoped>
.container {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
}

.header {
  display: flex;
  align-items: center;
  padding: 0 16px;
  height: 56px;
}

.logo {
  display: flex;
  align-items: center;
  justify-content: center;
}

.nav {
  display: flex;
  margin-left: auto;
  gap: 16px;
}

.nav-link {
  font-size: 14px;
  font-weight: 500;
  text-decoration: none;
  color: inherit;
  transition: text-decoration-color 0.2s;
}

.nav-link:hover {
  text-decoration: underline;
  text-underline-offset: 4px;
}

.main {
  flex: 1;
}

.intro,
.categories,
.featured,
.mission {
  padding: 48px 16px;
}

.intro {
  display: flex;
  justify-content: center;
  align-items: center;
}

.intro-container {
  display: grid;
  gap: 24px;
  align-items: center;
  max-width: 610px;
}


.intro-text {
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.title {
  font-size: 36px;
  font-weight: bold;
}

.description {
  max-width: 600px;
  color: #666;
}

.cta {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  height: 40px;
  padding: 0 32px;
  font-size: 14px;
  font-weight: 500;
  color: #fff;
  background-color: #FF6B6B;
  border-radius: 8px;
  text-decoration: none;
  transition: background-color 0.2s;
}

.cta:hover {
  background-color: #FF4D4D;
}

.intro-image {
  width: 100%;
  height: auto;
  border-radius: 16px;
  object-fit: cover;
  max-width: 610px;
  justify-self: left;
  align-self: first baseline;
}

.categories-container,
.featured-container,
.mission-container {
  margin: 0 auto;
}

.categories-header,
.featured-header,
.mission-header {
  text-align: center;
  margin-bottom: 32px;
}

.svg-categories {
  height: 48px;
  width: 48px;
  color: #FF6B6B;
}

.div-loader {
  display: flex;
  justify-content: center;
  align-items: center;

}

.loader {
  width: 150px;
  height: 150px;
  margin: 0 auto;
  animation: spin 2s linear infinite;
}

@keyframes spin {
  0% {
    transform: rotate(0deg);
  }

  100% {
    transform: rotate(360deg);
  }
}

.categories-title,
.featured-title,
.mission-title {
  font-size: 36px;
  font-weight: bold;
}

.categories-description,
.featured-description,
.mission-description,
.values-description {
  margin: 0 auto;
  color: #666;
}

.categories-grid,
.featured-grid {
  display: grid;
  gap: 24px;
  grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
}

.category-item,
.card {
  display: flex;
  flex-direction: column;
  align-items: center;
  padding: 16px;
  background-color: #fff;
  border-radius: 8px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  transition: background-color 0.2s;
  text-decoration: none;
  color: inherit;
}

.category-item:hover,
.card:hover {
  background-color: #f9f9f9;
}

.category-icon,
.card-icon {
  height: 48px;
  width: 48px;
  color: #FF6B6B;
}

.category-name,
.card-title {
  font-size: 18px;
  font-weight: bold;
  margin-top: 16px;
  margin-bottom: 8px;
}

.card-image {
  width: 100%;
  height: auto;
  border-radius: 8px;
  object-fit: cover;
  max-height: 170px;
}

.card-content {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.card-description {
  color: #666;
  flex: 1;
}

.card-footer {
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.card-price {
  font-size: 16px;
  font-weight: bold;
}

.card-cta {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  height: 32px;
  padding: 0 16px;
  font-size: 14px;
  font-weight: 500;
  color: #fff;
  background-color: #FF6B6B;
  border-radius: 8px;
  text-decoration: none;
  transition: background-color 0.2s;
}

.card-cta:hover {
  background-color: #FF4D4D;
}

.mission-container {
  display: grid;
  gap: 24px;
}

.mission-text,
.values-text {
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.mission-header,
.values-header {
  font-size: 24px;
  font-weight: bold;
}

.values-cta {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  height: 40px;
  padding: 0 32px;
  font-size: 14px;
  font-weight: 500;
  color: #fff;
  background-color: #FF6B6B;
  border-radius: 8px;
  text-decoration: none;
  transition: background-color 0.2s;
}

.values-cta:hover {
  background-color: #FF4D4D;
}

.footer {
  height: 400px;
}

@media (min-width: 1200px) {

  .intro,
  .categories,
  .featured,
  .mission {
    padding: 64px 0;
  }

  .intro-container {
    display: flex;
    max-width: none;
    width: 100%;
    justify-content: space-around;
  }

  .intro-container,
  .categories-container,
  .featured-container,
  .mission-container {
    margin-left: 48px;
    margin-right: 48px;
  }

  .intro-text {
    max-width: 600px;
  }

  .intro-image {
    border-radius: 16px;
  }

  .categories-grid,
  .featured-grid {
    grid-template-columns: repeat(3, 1fr);
  }


}
</style>