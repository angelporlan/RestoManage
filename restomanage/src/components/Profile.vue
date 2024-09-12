<template>
  <MessageModal class="message-modal" v-if="showMessageModal" :message="message" :color="color"
    @close="showModal = false" />
  <EditUserModal class="modal" v-if="showModal" :name="user.name" :username="user.username" :address="user.address"
    @close="showModal = false" @save="updateUser" />
  <div class="container">
    <div v-if="user" class="profile">
      <div class="profile-card">
        <div class="avatar">
          <img class="avatar-image" src="@/assets/icons/user-image.svg" alt="Avatar" />
        </div>
        <div class="profile-info">
          <h2>{{ user.name }}</h2>
          <p>{{ user.username }}</p>
          <p>{{ user.email }}</p>
        </div>
        <div class="buttons">
          <button v-if="user.roles.includes('ROLE_ADMIN')" class="button-admin" @click="this.$router.push('/config')"><img class="svg-gear"
              src="@/assets/icons/gear.svg">Administrador</button>
          <button @click="showModal = !showModal" class="edit"><img class="svg-edit" src="@/assets/icons/edit-user.svg">
            Editar</button>

          <button class="close-sesion" @click="cerrarSesion"><img class="svg-logout"
              src="@/assets/icons/logout.svg">Cerrar Sesión</button>

        </div>
      </div>
      <div class="stats">
        <div class="stat-card">
          <h3>Compras Totales</h3>
          <div class="stat-value">{{ user.totalOrders }}</div>
        </div>
        <div class="stat-card">
          <h3>Gasto Total</h3>
          <div class="stat-value">{{ user.totalSpent.toFixed(2) }} €</div>
        </div>
      </div>

      <div class="orders" v-if="paginatedOrders && paginatedOrders.length > 0">
        <h2>Últimos pedidos</h2>
        <ul>
          <li v-for="order in paginatedOrders" :key="order.id">
            <p>{{ formatDate(order.date) }}</p>
            <div class="dishes" v-for="dish in order.dishes" :key="dish.id">
              <img :src="require('@/assets/dishes/' + dish.foto)" alt="Imagen del plato" />
              <p class="dish-nombre">{{ dish.nombre }}</p>
              <p class="dish-cantidad">{{ dish.cantidad }} u.</p>
              <p class="dish-precio">{{ dish.precio }} €</p>
            </div>
            <p class="total-order">Total: {{ order.total.toFixed(2) }} €</p>
          </li>
        </ul>
        <div class="pagination">
          <button @click="prevPage" :disabled="currentPage === 1">Anterior</button>
          <span>Página {{ currentPage }} de {{ totalPages }}</span>
          <button @click="nextPage" :disabled="currentPage === totalPages">Siguiente</button>
        </div>
      </div>

    </div>
    <div v-else class="loading">
      <p>Cargando</p>
    </div>
  </div>
</template>


<script>
import { mapActions, mapGetters } from 'vuex';
import EditUserModal from './modals/EditUserModal.vue';
import userService from '@/services/UserService.js';
import MessageModal from './modals/MessageModal.vue';

import { format } from 'date-fns'
import { es } from 'date-fns/locale'

export default {
  name: 'ProfileComponent',
  components: {
    EditUserModal,
    MessageModal,
  },
  data() {
    return {
      showModal: false,
      user: null,
      currentPage: 1,
      ordersPerPage: 3,
      showMessageModal: false,
      message: '',
      color: '',

    };
  },
  computed: {
    ...mapGetters('user', ['getUser']),
    paginatedOrders() {
      const start = (this.currentPage - 1) * this.ordersPerPage;
      const end = start + this.ordersPerPage;
      return this.user ? [...this.user.orders].reverse().slice(start, end) : [];
    },
    totalPages() {
      return this.user ? Math.ceil(this.user.orders.length / this.ordersPerPage) : 1;
    },

  },
  created() {
    this.recuperarUserDeVuex();
  },
  methods: {
    ...mapActions('user', ['setUser']),
    recuperarUserDeVuex() {
      const savedUser = this.getUser;
      if (savedUser) {
        this.user = savedUser;
      } else {
        this.$router.push('/login');
      }
    },

    cerrarSesion() {
      this.setUser(null);
      this.$router.push('/');
    },

    updateUser(data) {
      const savedUser = this.getUser;
      savedUser.name = data.name;
      savedUser.username = data.username;
      savedUser.address = data.address;

      this.setUser(savedUser);
      userService.updateUser(savedUser.id, savedUser);

      this.showModal = false;

      this.message = 'Usuario actualizado';
      this.color = 'green';
      this.showMessageModal = true;
      setTimeout(() => {
        this.showMessageModal = false;
      }, 4000);

    },
    formatDate(date) {
      return format(new Date(date), "d 'de' MMMM yyyy 'a las' HH:mm", { locale: es });
    },
    nextPage() {
      if (this.currentPage < this.totalPages) {
        this.currentPage++;
      }
    },
    prevPage() {
      if (this.currentPage > 1) {
        this.currentPage--;
      }
    },
  },
};
</script>



<style>
.modal {
  position: fixed !important;
  top: 0;
}

.message-modal {
  position: fixed !important;
  top: 12px !important;
  left: calc(50% - 125px);
}

.container {
  margin: 0 auto;
  padding: 2rem;
}

.profile {
  display: grid;
  grid-template-columns: 300px 1fr;
  gap: 2rem;
  align-items: start;
}

.svg-edit {
  width: 14px;
  height: 14px;
}

.svg-logout {
  width: 14px;
  height: 14px;
  margin-right: 6px;
}

.edit {
  padding: 1rem 2rem;
  background-color: #0272b3;
  border: none;
  border-radius: 10px;
  cursor: pointer;
  font-size: 1.2rem;
  color: white;

}

.profile-card {
  background-color: #f3f3f3;
  border-radius: 10px;
  padding: 1.5rem;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 1rem;
  margin-top: 10px;
}

.avatar {
  width: 80px;
  height: 80px;
  position: relative;
  border-radius: 50%;
  overflow: hidden;
}

.avatar img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.profile-info {
  text-align: center;
}

.profile-info h2 {
  font-size: 1.5rem;
  font-weight: bold;
}

.profile-info p {
  color: #777;
}

.stats {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 1rem;
}

.stat-card {
  background-color: #f3f3f3;
  border-radius: 10px;
  padding: 1rem;
  text-align: center;
}

.stat-card h3 {
  font-size: 1.2rem;
}

.stat-value {
  font-size: 2rem;
  font-weight: bold;
}

.orders {
  margin-top: 2rem;
  grid-column: span 2;
}

.orders h2 {
  font-size: 1.5rem;
  margin-bottom: 1rem;
}

.orders ul {
  list-style: none;
  padding: 0;
}

.orders li {
  margin-bottom: 1rem;
  border: 1px solid #ccc;
  padding: 1rem;
  border-radius: 10px;
}

.orders p {
  margin: 0;
}

.dishes {
  display: flex;
  gap: 1rem;
  align-items: center;
}

.dishes img {
  width: 50px;
  height: 50px;
  object-fit: cover;
  border-radius: 10px;
}

.dish-nombre {
  font-size: 1.2rem;
}

.total-order {
  margin-left: auto;
}

.dish-cantidad {
  font-size: 1rem;
  color: #777;
}

.dish-precio {
  font-size: 1.2rem;
  font-weight: bold;
}

.buttons {
  margin-top: 2rem;
  width: 100%;
}

.close-sesion,
.edit {
  width: 100%;
}

.close-sesion {
  padding: 1rem 2rem;
  background-color: #ff6b6b;
  color: white;
  border: none;
  border-radius: 10px;
  cursor: pointer;
  font-size: 1.2rem;
  margin-top: 1rem;
}

.loading {
  text-align: center;
}

.loading p {
  font-size: 1.5rem;
}

.pagination {
  display: flex;
  justify-content: center;
  align-items: center;
  margin-top: 1rem;
  gap: 1rem;
}

.pagination button {
  padding: 0.5rem 1rem;
  background-color: #f3f3f3;
  border: 1px solid #ccc;
  border-radius: 5px;
  cursor: pointer;
}

.pagination button:disabled {
  cursor: not-allowed;
  background-color: #e0e0e0;
}

.pagination span {
  font-size: 1rem;
}

.orders {
  margin-top: 2rem;
  grid-column: span 2;
}

.orders h2 {
  font-size: 1.5rem;
  margin-bottom: 1rem;
  color: #333;
}

.orders ul {
  list-style: none;
  padding: 0;
  margin: 0;
}

.orders li {
  margin-bottom: 1rem;
  border: 1px solid #ddd;
  padding: 1rem;
  border-radius: 10px;
  background-color: #fff;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.orders p {
  margin: 0.5rem 0;
  color: #555;
}

.dishes {
  display: flex;
  flex-wrap: wrap;
  gap: 1rem;
  align-items: center;
  margin-top: 0.5rem;
  padding-bottom: 8px;
  border-bottom: 1px solid #ddd;
}

.dishes img {
  width: 50px;
  height: 50px;
  object-fit: cover;
  border-radius: 10px;
  border: 1px solid #ddd;
}

.dish-nombre {
  font-size: 1rem;
  flex: 1;
  color: #333;
}

.dish-cantidad,
.dish-precio {
  font-size: 0.9rem;
  color: #777;
}

.dish-precio {
  font-weight: bold;
}

.pagination {
  display: flex;
  justify-content: center;
  align-items: center;
  margin-top: 1rem;
  gap: 1rem;
}

.pagination button {
  padding: 0.5rem 1rem;
  background-color: #0272b3;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-size: 1rem;
  transition: background-color 0.3s ease;
}

.pagination button:hover {
  background-color: #025a91;
}

.pagination button:disabled {
  cursor: not-allowed;
  background-color: #e0e0e0;
}

.pagination span {
  font-size: 1rem;
  color: #555;
}

.button-admin {
  padding: 1rem 2rem;
  background-color: #c7e00b;
  color: white;
  border: none;
  border-radius: 10px;
  cursor: pointer;
  font-size: 1.2rem;
  margin-bottom: 1rem;
  width: 100%;
}

.svg-gear {
  width: 14px;
  height: 14px;
  margin-right: 6px;
}

@media (max-width: 820px) {
  .profile {
    grid-template-columns: 1fr;
  }

  .stats {
    grid-template-columns: 1fr;
  }

  .orders {
    grid-column: span 1;
  }

  .buttons {
    display: flex;
    flex-direction: column;
    gap: 1rem;
  }

  .close-sesion {
    width: 100%;
  }

  .pagination {
    flex-direction: column;
    gap: 0.5rem;
  }

  .pagination button {
    width: 100%;
  }
}
</style>
