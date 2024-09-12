<template>
    <div class="modal">
        <div class="modal-content">
            <img class="cancel" src="@/assets/icons/cancel.svg" @click="close">
            <div class="inputs-box">
                <input type="file" class="input-file" @change="previewImage" ref="fileInput">
                <input placeholder="Nombre del plato" v-model="nombre">
                <input type="number" placeholder="Precio del plato" v-model="precio">
                <input placeholder="Descripción del plato" v-model="descripcion">
                <input type="number" v-model="peso" placeholder="peso">
                <select>
                    <option value="Entrantes">Entrantes</option>
                    <option value="Principales">Principales</option>
                    <option value="Postres">Postres</option>
                </select>
                <input placeholder="Ingredientes" v-model="ingredientes">
                <input type="proteinas" v-model="proteinas" placeholder="proteinas">
                <input type="hidratos" v-model="hidratos" placeholder="hidratos">
                <input type="calorias" v-model="calorias" placeholder="grasas">
                <input placeholder="Descripción de la preparación del plato" v-model="uso">

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
  name: 'AddDishModal',
  data() {
    return {
      nombre: '',
      foto: '',
      precio: null,
      descripcion: '',
      peso: null,
      categoria: 'Entrantes',
      ingredientes: '',
      proteinas: null,
      hidratos: null,
      calorias: null,
      uso: ''
    };
  },
  methods: {
    close() {
      this.$emit('close');
    },
    convertToBase64(file) {
      return new Promise((resolve, reject) => {
        const reader = new FileReader();

        reader.onload = () => {
          resolve(reader.result.split(',')[1]);
        };

        reader.onerror = () => {
          reject('Error al leer el archivo');
        };

        reader.readAsDataURL(file);
      });
    },
    async save() {
  try {
    this.foto = await this.convertToBase64(this.$refs.fileInput.files[0]);

    const nuevoPlato = {
      nombre: this.nombre,
      foto: this.foto,
      precio: this.precio,
      descripcion: this.descripcion,
      peso: this.peso + 'g',
      categoria: this.categoria,
      ingredientes: this.ingredientes,
      infoNutri: {
        proteina: this.proteinas !== null ? `${this.proteinas}g` : null,
        hidratos: this.hidratos !== null ? `${this.hidratos}g` : null,
        grasas: this.grasas !== null ? `${this.grasas}g` : null,
      },
      uso: this.uso.split(',').map(item => item.trim())
    };


    this.$emit('save', nuevoPlato);
  } catch (error) {
    console.error('Error al crear plato:', error);
  }
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

.modal-content {
    background-color: #fff;
    padding: 24px;
    border-radius: 8px;
    width: 100%;
    max-width: 800px;
    position: relative;
    padding-top: 38px;
}

input, select {
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
    position: absolute;
    width: 20px;
    cursor: pointer;
    margin-right: auto;
    left: 12px;
    top: 12px;
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
    margin-bottom: 0;
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
    margin-bottom: 0;

}

.buttons {
    margin-top: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 24px;
    width: calc(100% - 48px);
    margin-left: 0px;
    margin-bottom: 0;
    width: 100%;
}

.inputs-box {

    display: grid;
    grid-template-columns: 1fr 1fr;
}

</style>
