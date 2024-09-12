<template>
  <div class="accordion">
    <div v-for="(title, index) in titles" :key="index" class="accordion-item">
      <button @click="toggleSection(index)" class="accordion-header">
        {{ title }}
        <img :class="{'arrow': true, 'rotate': isOpen(index)}" src="@/assets/icons/down-arrow.svg" />
      </button>
      <div v-show="isOpen(index)" class="accordion-content">
        <template v-if="index === 0">
          <p class="ingredients">{{ contents[0] }}</p>
        </template>
        <template v-else-if="index === 1">
          <table>
            <thead>
              <tr>
                <th>Nutriente</th>
                <th>Valor</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(value, key) in contents[1]" :key="key">
                <td><strong>{{ key }}</strong></td>
                <td>{{ value }}</td>
              </tr>
            </tbody>
          </table>
        </template>
        <template v-else-if="index === 2">
          <ol>
            <li v-for="(step, i) in contents[2]" :key="i">
              {{ step }}
            </li>
          </ol>
        </template>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "AccordionComponent",
  props: {
    contents: {
      type: Array,
      required: true
    }
  },
  data() {
    return {
      titles: ["Ingredientes", "Valor nutricional", "Modo de empleo"],
      openSections: []
    };
  },
  methods: {
    toggleSection(index) {
      if (this.openSections.includes(index)) {
        this.openSections = this.openSections.filter(i => i !== index);
      } else {
        this.openSections.push(index);
      }
    },
    isOpen(index) {
      return this.openSections.includes(index);
    }
  }
};
</script>

  
  <style scoped>

.accordion-item {
  margin-bottom: 1em;
}

.accordion-header {
  background-color: #f1f1f1;
  border: none;
  cursor: pointer;
  padding: 1em;
  width: 100%;
  text-align: left;
  font-size: 1.2em;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.accordion-content {
  padding: 12px 0;
  border: 1px solid #f1f1f1;
  border-top: none;
}

table {
  width: 100%;
  border-collapse: collapse;
}

thead {
  background-color: #f1f1f1;
}

th, td {
  padding: 0.5em;
  border: 1px solid #ddd;
  text-align: left;
}

th {
  font-weight: bold;
}

ol {
  margin: 0 34px;
}

.ingredients {
  margin: 0 18px;
}

.arrow {
  transition: transform 0.3s ease;
  width: 24px;
  height: 24px;
}

.rotate {
  transform: rotate(180deg);
}

  </style>
  