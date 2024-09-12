<template>
    <div class="card">
      <div class="card-header">
        <h2 class="card-title">{{percentageOff}}% En tu próxima compra</h2>
        <p class="card-description">
          Utiliza este código para un {{ percentageOff }}% en tu próximo pedido.
        </p>
      </div>
      <div class="card-content">
        <div class="coupon-code" @click="copyCode">
          {{couponCode}}
        </div>
        <button class="copy-button" @click="copyCode">
            Copiar código
        </button>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    name: "CuponComponent",
    props: {
      couponCode: {
        type: String,
        required: true,
      },
      percentageOff: {
        type: Number,
        required: true,
      },
    },
    methods: {
      copyCode() {
        navigator.clipboard.writeText(this.couponCode);
        this.$emit("copied");
    },
    },
  };
  </script>
  
  <style scoped>
  .card {
    width: 100%;
    max-width: 400px;
    background-color: #f8fafc;
    border: 1px solid #e5e7eb;
    border-radius: 0.5rem;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    overflow: hidden;
  }
  
  .card-header {
    padding: 1.5rem;
    border-bottom: 1px solid #e5e7eb;
  }
  
  .card-title {
    font-size: 1.25rem;
    font-weight: 700;
    margin: 0 0 0.5rem;
  }
  
  .card-description {
    font-size: 0.875rem;
    color: #6b7280;
    margin: 0;
  }
  
  .card-content {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1rem 1.5rem;
  }
  
  .coupon-code {
    background-color: #f3f4f6;
    padding: 0.5rem 1rem;
    border-radius: 0.375rem;
    font-weight: 500;
    color: #111827;
    cursor: pointer;
    user-select: all;
  }
  
  .copy-button {
    padding: 0.5rem 1rem;
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
    background-color: #fff;
    color: #1f2937;
    font-weight: 500;
    cursor: pointer;
    transition: background-color 0.2s, color 0.2s;
  }
  
  .copy-button:hover {
    background-color: #f9fafb;
    color: #111827;
  }
  </style>
  