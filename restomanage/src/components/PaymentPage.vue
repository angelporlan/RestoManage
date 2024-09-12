<template>
    <div class="template">
        <MessageModal class="modal" v-if="modal" :message="messageModal" :color="color" />
        <div class="section1">
            <div class="payment-form">
                <h2 class="title">Entrega</h2>
                <input type="text" id="name" class="name" placeholder="Nombre" v-model="name">
                <input type="email" id="email" class="email" placeholder="Email" v-model="email">
                <input type="tel" id="phone" class="phone" placeholder="Telefono" v-model="phone">
                <input type="text" id="address" class="address" placeholder="Dirección" v-model="address">
                <input type="text" id="city" class="city" placeholder="Ciudad" v-model="city">
                <input type="text" id="zip" class="zip" placeholder="Código postal" v-model="zip">
            </div>

            <div class="pay">
                <h2 class="title2">Pago</h2>
                <div class="target">
                    <p>Tarjeta de crédito</p>
                    <img class="visa" src="@/assets/icons/visa.svg">
                    <img class="mastercard" src="@/assets/icons/mastercard.svg">
                </div>
                <input type="text" class="iban" id="iban" name="iban" placeholder="Número de tarjeta" v-model="iban">
                <input type="text" class="expired-date" id="expired-date" name="expired-date"
                    placeholder="Fecha de vencimiento" v-model="expiredDate">
                <input type="text" class="cvv" id="cvv" name="cvv" placeholder="CVV" v-model="cvv">
            </div>

            <div class="promotional-code">
                <h2 class="title3">Código promocional</h2>
                <div class="promotional-input">
                    <input type="text" class="code" id="code" name="code" placeholder="Introduce tu código"
                        v-model="discount">
                    <button class="apply" @click="aplicarDescuento">Aplicar</button>
                </div>
            </div>
        </div>

        <div class="cesta">
            <h2 class="title3">Tu pedido</h2>
            <ul>
                <li v-for="item in cesta" :key="item.codigo">
                    <img class="img-stock" :src="require('@/assets/dishes/' + item.foto)">
                    <div class="nombre-precio">
                        <h3>{{ item.nombre }}</h3>
                        <p>{{ (item.precio * item.cantidad).toFixed(2) }} €</p>
                    </div>
                    <p class="cantidad">{{ item.cantidad }}</p>
                </li>
            </ul>
            <div class="total">
                <div class="div" v-if="isDiscountAplied">
                    <p>Total</p>
                    <p class="undiscounted-price">{{ getPrice()[1] }} €</p>
                </div>
                <div class="div" v-if="isDiscountAplied">
                    <p>Descuento ({{ this.discountPercentage }} %)</p>
                    <p class="discount">-{{ (getPrice()[1] - getPrice()[0]).toFixed(2) }} €</p>
                </div>
                <div class="div">
                    <p>Total</p>
                    <p class="total-price">{{ getPrice()[0] }} €</p>
                </div>
            </div>
            <button class="payment" @click="validateForm">Pagar ahora</button>
        </div>
    </div>
</template>

<script>
import MessageModal from './modals/MessageModal.vue';
import userService from '@/services/UserService';
import { deleteReward } from '@/services/RewardService.js';

export default {
    name: 'PaymentComponent',
    components: {
        MessageModal
    },
    data() {
        return {
            discountPercentage: 0,
            isDiscountAplied: false,
            modal: false,
            messageModal: '',
            color: '',
            name: '',
            email: '',
            phone: '',
            address: '',
            city: '',
            zip: '',
            iban: '',
            expiredDate: '',
            cvv: '',
            discount: ''
        };
    },
    computed: {
        cesta() {
            return this.$store.getters['cesta/cesta'];
        },
    },
    created() {
        this.address = this.$store.getters['user/getUser'].address;
        this.name = this.$store.getters['user/getUser'].name;
        this.email = this.$store.getters['user/getUser'].email;
    },
    methods: {
        getPrice() {
            const total = this.cesta.reduce((acc, item) => acc + item.precio * item.cantidad, 0);

            const totalDiscounted = this.discountPercentage !== 0 ? total - (total * this.discountPercentage / 100) : total;

            return [totalDiscounted.toFixed(2), total.toFixed(2)];
        }
        ,
        validateForm() {
            if (!this.name || !this.email || !this.phone || !this.address || !this.city || !this.zip || !this.iban || !this.expiredDate || !this.cvv) {
                this.color = 'red';
                this.modal = true;
                this.messageModal = 'Todos los campos tienen que estar completos';
                setTimeout(() => {
                    this.modal = false;
                }, 4000);
                return;
            }

            const cardNumberPattern = /^(\d{4} \d{4} \d{4} \d{4}|\d{16})$/;
            if (!cardNumberPattern.test(this.iban)) {
                this.color = 'red';
                this.modal = true;
                this.messageModal = 'El número de tarjeta debe tener 16 dígitos';
                setTimeout(() => {
                    this.modal = false;
                }, 4000);
                return;
            }

            const expiredDatePattern = /^(0[1-9]|1[0-2])\/?([0-9]{2})$/;
            if (!expiredDatePattern.test(this.expiredDate)) {
                this.color = 'red';
                this.modal = true;
                this.messageModal = 'La fecha de vencimiento debe tener el formato MM/AA';
                setTimeout(() => {
                    this.modal = false;
                }, 4000);
                return;
            }

            const cvvPattern = /^\d{3}$/;
            if (!cvvPattern.test(this.cvv)) {
                this.color = 'red';
                this.modal = true;
                this.messageModal = 'El CVV debe tener 3 dígitos';
                setTimeout(() => {
                    this.modal = false;
                }, 4000);
                return;
            }

            const user = this.$store.getters['user/getUser'];
            user.totalSpent += parseFloat(this.getPrice()[0]);
            user.totalOrders += 1;
            user.ordersForDiscount += 1;
            user.counterMonneyOffer += parseFloat(this.getPrice()[0]);


            user.orders = [...user.orders, { date: Date.now(), total: parseFloat(this.getPrice()[0]), dishes: this.cesta }];


            this.$store.dispatch('user/setUser', user);
            userService.updateUser(user.id, user);  
            this.$store.dispatch('cesta/clearCesta');   
            this.$router.push('/profile');
        },
        async aplicarDescuento() {
            const user = this.$store.getters['user/getUser'];
            const p = await deleteReward(this.discount);
            if (p.error) {
                this.color = 'red';
                this.modal = true;
                this.messageModal = 'Código incorrecto';
                setTimeout(() => {
                    this.modal = false;
                }, 4000);
                return;
            }
            this.discountPercentage = p.value;
            this.color = 'green';
            this.modal = true;
            this.messageModal = 'Código aplicado correctamente';
            this.isDiscountAplied = true;
            this.$store.dispatch('user/setUser', user);
            userService.updateUser(user.id, user);
            setTimeout(() => {
                this.modal = false;
            }, 4000);

        }
    }
}
</script>



<style scoped>
.modal {
    position: fixed;
    top: 12px;
    left: calc(50% - 125px);
}

.payment-form {
    display: flex;
    flex-direction: column;
    margin: 20px;
}

label {
    margin-top: 10px;
    display: flex;
    flex-direction: column;
}

input {
    border: 1.25px solid rgb(84, 84, 84);
    height: 32px;
    padding-left: 12px;
    margin-top: 14px;
    border-radius: 4px;
}

.pay {
    display: flex;
    flex-direction: column;
    margin: 20px;
}

.pay input {
    margin-top: 14px;
    border: 1.25px solid rgb(84, 84, 84);
    height: 32px;
}

.target {
    display: flex;
    width: 100%;
    align-items: center;
    background-color: rgb(222, 222, 222);
    margin-top: 12px;
    padding: 12px;
}

.target img {
    width: 24px;
    height: auto;
}

.visa {
    margin-left: auto;
    background-color: white;
    padding: 4px;
    border-radius: 4px;
}


.mastercard {
    margin-left: 12px;
    background-color: white;
    padding: 4px;
    border-radius: 4px;

}

.payment {
    width: 100%;
    height: 48px;
    border-radius: 8px;
    background-color: orange;
    color: white;
    border: none;
    cursor: pointer;
    margin: 24px 0;
}

.img-stock {
    width: 70px;
    height: 70px;
    border-radius: 8px;
    object-fit: cover;
}


li {
    display: flex;
    justify-content: left;
    align-items: center;
    padding: 12px;
    padding-left: 0;
    border-bottom: 1px solid #ccc;
    position: relative;
}

.cantidad {
    background-color: rgb(137, 137, 137);
    border-radius: 26px;
    padding: 4px 12px;
    position: absolute;
    color: white;
    top: 8px;
    right: calc(100% - 82px);
}

.nombre-precio {
    margin: 0 12px;
}

.cesta {
    display: flex;
    flex-direction: column;
    margin: 20px;
}

.promotional-code {
    display: flex;
    flex-direction: column;
    margin: 20px;
}

.promotional-input {
    display: flex;
    align-items: center;
}

.promotional-input input,
.promotional-input button {
    border: 1.25px solid rgb(84, 84, 84);
    height: 32px;
    padding-left: 12px;
    margin-top: 14px;
}

.promotional-input input {
    width: 100%;
    border-right: none;
    border-radius: 4px 0 0 4px;
}

.promotional-input button {
    width: 100px;
    height: 32px;
    background-color: rgb(222, 222, 222);
    cursor: pointer;
    padding: 0;
    border: 1.25px solid rgb(84, 84, 84);
    border-left: none;
    border-radius: 0 4px 4px 0;
}

.total {
    margin-top: 12px;
}

.div {
    margin-top: 8px;
    display: flex;
    justify-content: space-between;
}

.undiscounted-price {
    text-decoration: line-through;
}

.discount {
    color: green;
}

.total-price {
    font-weight: bold;
    font-size: 20px;
}

@media (min-width: 500px) {
    .payment-form {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    input {
        margin-top: 0;
    }

    .title,
    .name,
    .email,
    .adress,
    .title2,
    .iban,
    .target {
        grid-column: 1 / 3;
    }

    .pay {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .pay input {
        margin-top: 0;
    }

    .target {
        margin-top: 0;
    }
}

@media (min-width: 1200px) {
    .template {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    input,
    .pay input,
    .promotional-input input,
    .promotional-input button {
        height: 36px;
        font-size: 16px;
    }

    .payment {
        height: 52px;
        font-size: 18px;
    }

    .template {
        margin-left: 24px;
        margin-right: 24px;
    }
}
</style>