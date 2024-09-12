<template>
    <div class="template">
        <h1 v-if="!isRegister">Inciar Sesión</h1>
        <h1 v-if="isRegister">Crear Cuenta</h1>
        <div class="form">
            <input type="text" v-model="email" placeholder="Email">
            <input type="password" v-model="password" placeholder="Contraseña">
            <div v-if="isRegister" class="additional-fields">
                <input type="text" v-model="name" placeholder="Nombre">
                <input type="text" v-model="username" placeholder="Nombre de usuario">
                <input type="text" v-model="address" placeholder="Dirección">
            </div>
            <button v-if="!isRegister" class="form-button" @click="login">Iniciar sesión</button>
            <button v-if="isRegister" class="form-button" @click="register">Crear cuenta</button>
        </div>
        <p class="text-bottom" v-if="isRegister">¿Tienes cuenta? <span @click="toggleForm">Inicia Sesión</span></p>
        <p class="text-bottom" v-else>¿No tienes cuenta? <span @click="toggleForm">Registrate</span></p>

        <MessageModal v-if="greenModal" class="message" :message="message" color="green" />
        <MessageModal v-if="redModal" class="message" :message="message" color="red" />
    </div>
</template>

<script>
import userService from '@/services/UserService.js';
import MessageModal from '@/components/modals/MessageModal.vue';
import { mapActions } from 'vuex';
import CryptoJS from 'crypto-js';

export default {
    name: 'LoginComponent',
    components: {
        MessageModal
    },
    data() {
        return {
            redModal: false,
            greenModal: false,
            message: '',
            isRegister: false,
            email: '',
            password: '',
            name: '',
            username: '',
            address: ''
        };
    },
    methods: {
        toggleForm() {
            this.isRegister = !this.isRegister;
        },
        ...mapActions('user', ['setUser']),
        async login() {
            
            if (!this.email || !this.password) {
                this.redModal = true;
                this.message = 'Todos los campos son obligatorios';
                setTimeout(() => {
                    this.redModal = false;
                }, 4000);
                return;
            }

            const user = await userService.getUserByEmailAndPassword(this.email, CryptoJS.SHA256(this.password).toString());
            if (!user) {
                this.redModal = true;
                this.message = 'Usuario o contraseña incorrectos';
                setTimeout(() => {
                    this.redModal = false;
                }, 4000);
            } else {
                this.setUser(user);

                this.$router.push('/menu');
            }
        },
        async register() {
            if (!this.email || !this.password || !this.name || !this.username || !this.address) {
                this.redModal = true;
                this.message = 'Todos los campos son obligatorios';
                setTimeout(() => {
                    this.redModal = false;
                }, 4000);
                return;
            }

            if (!this.isValidEmail(this.email)) {
                this.redModal = true;
                this.message = 'El email no es válido';
                setTimeout(() => {
                    this.redModal = false;
                }, 4000);
                return;
            }

            const emailUnique = await userService.checkEmailIsUnique(this.email);
            if (emailUnique) {
                this.redModal = true;
                this.message = 'El email ya está en uso';
                setTimeout(() => {
                    this.redModal = false;
                }, 4000);
                return;
            }

            if (this.password.length <= 4) {
                this.redModal = true;
                this.message = 'La contraseña debe tener más de 4 caracteres';
                setTimeout(() => {
                    this.redModal = false;
                }, 4000);
                return;
            }

            const hashedPassword = CryptoJS.SHA256(this.password).toString();

            const user = {
                email: this.email,
                password: hashedPassword,
                roles: ["ROLE_USER"],
                address: this.address,
                name: this.name,
                username: this.username,
                totalSpent: 0,
                totalOrders: 0,
                ordersForDiscount: 0,
                discountCode: [],
                orders: [],
                phone: null,
                counterMonneyOffer: 0,
            };

            userService.createUser(user);
            this.message = 'Usuario creado correctamente';
            this.greenModal = true;
            setTimeout(() => {
                this.greenModal = false;
            }, 4000);

            this.name = '';
            this.username = '';
            this.address = '';

            this.toggleForm();
        },

        isValidEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }
    }
};
</script>

<style scoped>
.template {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 20px;
    max-width: 400px;
    margin: 0 auto;
    border: 1px solid #ccc;
    border-radius: 5px;
    background-color: #f9f9f9;
    margin-top: 48px;
}

.text-bottom {
    margin-top: 12px;
    font-size: 16px;
}

h1 {
    margin-bottom: 24px;
    font-size: 24px;
    color: #333;
}

.form {
    width: 100%;
}

input {
    width: 100%;
    padding: 12px;
    margin-bottom: 12px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 16px;
}

.additional-fields {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
}

.form-button {
    width: 100%;
    padding: 12px;
    background-color: #0272b3;
    color: #fff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
    transition: background-color 0.3s;
}

.form-button:hover {
    background-color: #025aa5;
}

span {
    color: #0272b3;
    cursor: pointer;
}

.message {
    position: fixed;
    top: 12px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 1000;
    width: 90%;
    max-width: 400px;
    padding: 12px;
    border-radius: 5px;
    font-size: 16px;
    text-align: center;
}

.green {
    background-color: #7bc97f;
    color: #fff;
}

.red {
    background-color: #f44336;
    color: #fff;
}
</style>
