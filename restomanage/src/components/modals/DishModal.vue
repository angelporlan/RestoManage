<template>
  <div class="modal">
    <div class="modal-content">
      <img class="cancel" src="@/assets/icons/cancel.svg" @click="close">
      <div class="inputs-box">
        <input type="file" class="input-file" @change="previewImage" ref="fileInput">
        <!-- <button class="change-img" @click="clickToInput">Cambiar imagen</button> -->
        <img v-if="previewSrc" :src="previewSrc" alt="Previsualización de la imagen" class="imgprev"
          @click="triggerInput">
        <input placeholder="Nombre del plato" v-model="localDish.nombre">
        <input type="number" placeholder="Precio del plato" v-model="localDish.precio">
      </div>
      <div class="buttons">
        <button class="cancelar" @click="close">Cancelar</button>
        <button class="save" @click="save">Guardar</button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'DishModalComponent',
  props: {
    dish: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {
      localDish: { ...this.dish },
      previewSrc: null,
    };
  },
  methods: {
    clickToInput() {
      this.$refs.fileInput.click();
    },
    close() {
      this.$emit('close');
    },
    save() {
      if (this.$refs.fileInput.files[0]) {
        const file = this.$refs.fileInput.files[0];

        const reader = new FileReader();

        reader.onload = () => {
          let base64String = reader.result.split(',')[1];

          this.localDish.foto = base64String;

          const isNewImage = true;
          this.$emit('save', this.localDish, isNewImage);

          this.close();
        };

        reader.readAsDataURL(file);
      } else {
        const isNewImage = false;

        this.$emit('save', this.localDish, isNewImage);

        this.close();
      }
    },

    previewImage(event) {
      const file = event.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = (e) => {
          this.previewSrc = e.target.result;
        };
        reader.readAsDataURL(file);
      }
    },
    triggerInput() {
      this.$refs.fileInput.click();
    },
  },
  watch: {
    dish: {
      handler(newValue) {
        this.localDish = { ...newValue };
        this.previewSrc = newValue.foto ? require(`@/assets/dishes/${newValue.foto}`) : null;
      },
      deep: true,
      immediate: true,
    },
  },
};
</script>

<style scoped>
.modal {
  background: rgba(0, 0, 0, 0.6);
  padding: 20px;
  position: absolute;
  z-index: 3;
  width: 100%;
  height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
}

.modal-content {
  background-color: #fff;
  border-radius: 8px;
  padding: 20px;
  text-align: center;
  position: relative;
  padding-top: 190px;
}

.change-img {
  position: relative;
  background-color: #0272b3;
  color: #fff;
  border: none;
  cursor: pointer;
  width: calc(100% - 48px);
  height: 42px;
  border-radius: 8px;
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
  border: 1px solid #ccc;
}

.cancel {
  width: 20px;
  cursor: pointer;
  position: absolute;
  top: 20px;
  right: 20px;
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
  height: 42px;
  background-color: rgb(247, 77, 77);
  border-radius: 8px;
  margin-right: 24px;
}

.buttons {
  margin-top: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 24px;
  width: calc(100% - 48px);
  margin-left: 0px;
  margin-left: 24px;
}

input[type="file"] {
  display: none;
}

input[type="file"]::-webkit-file-upload-button {
  opacity: 0;
}

.imgprev {
  position: absolute;
  width: 145px;
  height: 145px;
  top: 32px;
  left: 48px;
  cursor: pointer;
}
</style>