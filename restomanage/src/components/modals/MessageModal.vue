<template>
    <div class="modal">
      <p class="message">{{ message }}</p>
      <div class="border-animation" :style="{ background: colorBorder }"></div>
    </div>
  </template>
  
  <script>
  export default {
    name: "MessageModal",
    props: ['message', 'color'],
    data() {
      return {
        colorBorder: null
      };
    },
    methods: {
      setColor() {
        if (this.color === 'green') {
          this.colorBorder = 'rgb(9, 223, 9)';
        } else if (this.color === 'red') {
          this.colorBorder = 'rgb(223, 9, 9)';
        } else {
          this.colorBorder = 'rgb(223, 223, 9)';
        }
      }
    },
    mounted() {
      this.setColor();
    }
  };
  </script>
  
  <style scoped>
  .modal {
    background: rgba(0, 0, 0, 0.6);
    padding: 20px;
    border-radius: 8px;
    position: absolute;
    animation: slideInFromRight 0.3s ease-in-out;
    z-index: 3;
    width: 250px;
    text-align: center;
  }
  
  .message {
    color: #fff;
    font-size: 18px;
  }
  
  .border-animation {
    height: 4px;
    width: 100%;
    animation: reduceWidth 4s linear forwards;
    position: absolute;
    bottom: 0;
    left: 0;
    border-radius: 8px;
  }
  
  @keyframes slideInFromRight {
    from {
      transform: translateX(100%);
    }
    to {
      transform: translateX(0);
    }
  }
  
  @keyframes reduceWidth {
    from {
      width: 100%;
    }
    to {
      width: 0;
    }
  }
  </style>