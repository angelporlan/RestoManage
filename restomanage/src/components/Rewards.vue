<template>
  <div>
    <MessageModal class="message" :message="message" :color="color" v-if="visiblemodal" />
    <div class="rewards">
      <div class="reward1">
        <div class="circle" :style="`--percentage: ${pricePercentage(user?.counterMonneyOffer || 0)}%;`">
          <span>{{ pricePercentage(user?.counterMonneyOffer || 0) }} %</span>
        </div>
        <div class="reward-text">
          <h3 v-if="pricePercentage(user?.counterMonneyOffer || 0) >= 100">¡Enhorabuena!</h3>
          <p v-if="pricePercentage(user?.counterMonneyOffer || 0) >= 100">Has conseguido un 10% de descuento</p>
          <p v-if="pricePercentage(user?.counterMonneyOffer || 0) < 100">Gasta 100€ en pedidos para un cupón de 10% de descuento ({{ user?.counterMonneyOffer.toFixed(2) || 0 }}€ - 100€)</p>
          <button v-if="pricePercentage(user?.counterMonneyOffer || 0) >= 100" @click="cuponMoney()">Reclamar cupón</button>
        </div>
      </div>

      <div class="reward2">
        <div class="circle" :style="`--percentage: ${orderPercentage(user?.ordersForDiscount || 0)}%;`">
          <span>{{ orderPercentage(user?.ordersForDiscount || 0) }} %</span>
        </div>
        <div class="reward-text">
          <h3 v-if="orderPercentage(user?.ordersForDiscount || 0) >= 100">¡Enhorabuena!</h3>
          <p v-if="orderPercentage(user?.ordersForDiscount || 0) >= 100">Has conseguido un 20% de descuento en tu próximo pedido</p>
          <p v-if="orderPercentage(user?.ordersForDiscount || 0) < 100">Realiza 5 pedidos para un cupón de 20% de descuento ({{ user?.ordersForDiscount || 0 }} de 5)</p>
          <button v-if="orderPercentage(user?.ordersForDiscount || 0) >= 100" @click="cuponOrders()">Reclamar cupón</button>
        </div>
      </div>
    </div>

    <div class="cupones" v-if="user && user.discountCode.length > 0">
      <h2>Códigos de descuento</h2>

      <div class="cupones-list">
        <Cupon
          v-for="code in user.discountCode"
          :key="code.code"
          :couponCode="code.code"
          :percentageOff="code.value"
          @copied="showCopiedMessage"
        />
      </div>
    </div>

    <div class="no-cupones" v-if="user && user.discountCode.length <= 0">
      <h2>No tienes códigos de descuento</h2>
    </div>
  </div>
</template>


<script>
import MessageModal from './modals/MessageModal.vue';
import {createReward, getRewards} from '@/services/RewardService.js';
import userService from '@/services/UserService.js';
import Cupon from '@/components/Cupon.vue';

export default {
  name: 'RewardsComponent',
  components: {
    MessageModal,
    Cupon,
  },
  data() {
    return {
      visiblemodal: false,
      message: '',
      color: '',
    };
  },
  created() {
    if (!this.user) {
      this.$router.push('/login');
    }
  },
  computed: {
    user() {
      return this.$store.getters['user/getUser'] || null;
    },
  },
  mounted() {
    if (this.user) {
      this.updateDiscounts();
    }
  },
  methods: {
    async updateDiscounts() {
      if (!this.user) return;

      try {
        const currentCoupons = this.user.discountCode;
        const newCoupons = await getRewards();

        const validCoupons = currentCoupons.filter(currentCoupon =>
          newCoupons.some(newCoupon => newCoupon.code === currentCoupon.code)
        );

        this.user.discountCode = validCoupons;
        this.$store.dispatch('user/setUser', this.user);
        await userService.updateUser(this.user.id, this.user);

      } catch (error) {
        console.error('Error updating discounts:', error);
      }
    },
    pricePercentage(counterMonneyOffer) {
      const targetAmount = 100;
      return Math.min(Math.floor((counterMonneyOffer / targetAmount) * 100), 100);
    },
    orderPercentage(ordersForDiscount) {
      const targetOrders = 5;
      return Math.min(Math.floor((ordersForDiscount / targetOrders) * 100), 100);
    },
    async cuponMoney() {
      this.user.counterMonneyOffer = 0;
      const cupon10 = await createReward(10);
      this.user.discountCode.push(cupon10);
      this.visiblemodal = true;
      this.message = 'Cupón de 10% de descuento añadido';
      this.color = 'green';
      setTimeout(() => {
        this.visiblemodal = false;
      }, 4000);
      this.$store.dispatch('user/setUser', this.user);
      userService.updateUser(this.user.id, this.user);
    },
    async cuponOrders() {
      this.user.ordersForDiscount = 0;
      const cupon20 = await createReward(20);
      this.user.discountCode.push(cupon20);
      this.visiblemodal = true;
      this.message = 'Cupón de 20% de descuento añadido';
      this.color = 'green';
      setTimeout(() => {
        this.visiblemodal = false;
      }, 4000);
      this.$store.dispatch('user/setUser', this.user);
      userService.updateUser(this.user.id, this.user);
    },
    showCopiedMessage() {
      this.visiblemodal = true;
      this.message = 'Código copiado al portapapeles';
      this.color = 'green';
      setTimeout(() => {
        this.visiblemodal = false;
      }, 4000);
    },
  },
}
</script>




<style scoped>
.message {
  position: absolute;
  top: 12px;
  left: calc(50% - 125px);
}
.rewards {
  display: flex;
  flex-direction: column;
  align-items: center;
  margin-top: 20px;
}

.reward1,
.reward2 {
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 20px 0;
  width: 100%;
  max-width: 500px;
  background-color: #fff;
  padding: 20px;
  border-radius: 12px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  transition: transform 0.3s;
}

.reward1:hover,
.reward2:hover {
  transform: translateY(-5px);
}

.circle {
  width: 150px;
  height: 150px;
  max-width: 150px;
  max-height: 150px;
  min-height: 150px;
  min-width: 150px;
  border-radius: 50%;
  background-color: #f5f5f5;
  display: flex;
  justify-content: center;
  align-items: center;
  position: relative;
}

.circle::before {
  content: "";
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  border-radius: 50%;
  background: conic-gradient(#4caf50 var(--percentage), #e0e0e0 0);
}

.circle span {
  position: relative;
  z-index: 1;
  font-size: 1.2rem;
  font-weight: bold;
  color: #333;
}

.reward-text {
  margin-left: 20px;
  text-align: center;
}

.reward-text h3 {
  font-size: 1.8rem;
  margin: 0;
  color: #4caf50;
}

.reward-text p {
  font-size: 1rem;
  margin: 10px 0;
  color: #666;
}

.reward-text button {
  padding: 10px 20px;
  background-color: #4caf50;
  color: white;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  transition: background-color 0.3s;
}

.reward-text button:hover {
  background-color: #45a049;
}

.cupones,
.no-cupones {
  width: 100%;
  margin: 20px auto;
  text-align: center;
}

.cupones h2,
.no-cupones h2 {
  font-size: 1.5rem;
  color: #333;
  margin-bottom: 20px;
}

.cupones-list {
  display: flex;
  justify-content: center;
  gap: 10px;
  flex-wrap: wrap;
}

@media (max-width: 600px) {
  .reward1,
  .reward2 {
    flex-direction: column;
    padding: 15px;
  }

  .reward-text {
    margin-left: 0;
    margin-top: 20px;
  }

}

@media (min-width: 1200px) {
  .rewards {
    flex-direction: row;
    justify-content: space-around;
    padding: 24px;
  }



  .cupones,
  .no-cupones {
    width: 100%;
    margin: 20px 0;
  }
}

</style>
