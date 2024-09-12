<template>
  <header>
    <h1 @click="this.$router.push('/')">RestoManage</h1>

    <router-link class="router router2" to="/menu">Menu</router-link>
    <router-link class="router" to="/rewards">Descuentos</router-link>

    <img class="svg-rewards" src="@/assets/icons/rewards.svg" @click="this.$router.push('/rewards')">
    <img class="svg-menu" src="@/assets/icons/menu.svg" @click="this.$router.push('/menu')">
    <img src="@/assets/icons/user-icon.svg" @click="this.$router.push('/profile')">
    <img class="cesta" src="@/assets/icons/cesta.svg" @click="showBasket = !showBasket" :style="basketStyle">
    <Basket :show="showBasket" @close="showBasket = !showBasket" />
    <p v-if="this.$store.getters['cesta/cesta'].reduce((acc, item) => acc + item.cantidad, 0) > 0" class="items-cesta">{{this.$store.getters['cesta/cesta'].reduce((acc, item) => acc + item.cantidad, 0)  }}</p>
  </header>
</template>

<script>
import Basket from '@/components/modals/BascketModal.vue';

export default {
  name: 'HeaderComponent',
  components: {
    Basket,
  },
  data() {
    return {
      showBasket: false,
      itemsCount: 0,
    };
  },
  computed: {
    basketStyle() {
      return `--items: '${this.itemsCount}'`;
    },
    isUserAdmin() {
      const user = this.getUser();
      if (user && user.roles) {
        return user.roles.includes('ROLE_ADMIN');
      }
      return false;
    }
  },
  created() {
    this.basketItemsCount();
    this.getUser();
  },
  methods: {
    basketItemsCount() {
      const cesta = this.$store.getters['cesta/cesta'];
      let totalItems = 0;
      for (let item of cesta) {
        totalItems += item.cantidad;
      }
      this.itemsCount = totalItems;
    },
    getUser() {
      return this.$store.getters['user/getUser'];
    },
  },
}
</script>



<style scoped>
header {
  background-color: #0272b3;
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
  padding: 24px 48px;
  position: relative;
}

.items-cesta {
  position: absolute;
  top: 22px;
  right: 40px;
  background-color: #FF4D4D;
  color: white;
  border-radius: 50%;
  padding: 2px 6px;
  font-size: 12px;
  z-index: 9;
}

.cesta {
  position: relative;
}

.svg-rewards, .svg-menu{
  display: none;
}

h1 {
  color: white;
  font-family: Arial, Helvetica, sans-serif;
  font-size: 32px;
  font-weight: 600;
  margin: 0;
  flex-grow: 1;
  cursor: pointer;
}

img {
  width: 32px;
  height: auto;
  margin-left: 10px;
  cursor: pointer;
}

.hamburger {
  display: none;
}

.router {
  color: white;
  text-decoration: none;
  margin: 0 10px;
  font-family: Arial, Helvetica, sans-serif;
  font-size: 18px;
}


@media (max-width: 590px) {

  .router {
    display: none;
  }

  .svg-rewards, .svg-menu {
    display: block;
    width: 32px;
    height: auto;
  }

  header {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr 1fr;

  }

  h1 {
    grid-column: 1 / span 4;
    align-self: center;
    justify-self: center;
  }

  img {
    margin: 24px 0 0 0;
    justify-self: center;
    align-self: center;
  }

}

</style>