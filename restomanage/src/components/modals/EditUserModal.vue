<template>
    <div class="modal">

        <body>
            <img class="cancel" src="@/assets/icons/cancel.svg" @click="close">
            <div class="inputs-box">
                <label>
                    Nombre
                    <input placeholder="Nombre" v-model="localName">
                </label>
                <label>
                    Usuario
                    <input placeholder="Usuario" v-model="localUsername">
                </label>
                <label>
                    Dirección
                    <input placeholder="Dirección" v-model="localAddress">
                </label>
            </div>
            <div class="buttons">
                <button class="cancelar" @click="close">Cancelar</button>
                <button class="save" @click="save">Guardar</button>

            </div>
        </body>

    </div>
</template>

<script>
export default {
    name: "EditUserModal",
    props: ['name', 'username', 'address'],
    data() {
        return {
            localName: this.name,
            localUsername: this.username,
            localAddress: this.address
        };
    },
    methods: {
        close() {
            this.$emit('close');
        },
        save() {
            this.$emit('save', {
                name: this.localName,
                username: this.localUsername,
                address: this.localAddress
            });
        }
    }
};
</script>

<style scoped>

.modal {
    background: rgba(0, 0, 0, 0.6);
    padding: 20px;
    position: absolute;
    z-index: 3;
    width: 250px;
    text-align: center;
    width: 100%;
    height: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
}

input {
    margin: 10px 24px;
    padding: 5px;
    width: calc(100% - 48px);
    height: 42px;
    border-radius: 5px;
    border: 1px solid #ccc;
}

label {
    text-align: left;
}

body {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background-color: #fff;
    border-radius: 8px;
}

.cancel {
    width: 20px;
    cursor: pointer;
    margin-right: auto;
    margin-left: 24px;
    margin-top: 24px;
    margin-bottom: 10px;
}

.save {
    padding: 5px 10px;
    background-color: #0272b3;
    color: #fff;
    border: none;
    cursor: pointer;
    width: 100%;
    height: 42px;
    border-radius: 8px;
}

.cancelar {
    padding: 5px 10px;
    color: #fff;
    border: none;
    cursor: pointer;
    width: 100%;
    margin-right: 24px;
    height: 42px;
    background-color: rgb(247, 77, 77);
    border-radius: 8px;
}

.buttons {
    margin-top: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 24px;
    width: calc(100% - 48px);
    margin-left: 0px;
}


</style>
