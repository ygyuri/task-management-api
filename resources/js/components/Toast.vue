<template>
    <Transition name="fade">
      <div v-if="visible" class="toast" :class="type">
        {{ message }}
      </div>
    </Transition>
  </template>
  
  <script setup>
  import { ref } from "vue";
  
  const visible = ref(false);
  const message = ref("");
  const type = ref("success");
  
  const showToast = (msg, toastType = "success") => {
    message.value = msg;
    type.value = toastType;
    visible.value = true;
    setTimeout(() => {
      visible.value = false;
    }, 3000);
  };
  
  defineExpose({ showToast });
  </script>
  
  <style scoped>
  .toast {
    position: fixed;
    bottom: 20px;
    right: 20px;
    background-color: #444;
    color: #fff;
    padding: 12px 16px;
    border-radius: 5px;
    font-size: 14px;
    opacity: 0.9;
  }
  .success {
    background-color: #4caf50;
  }
  .error {
    background-color: #f44336;
  }
  .fade-enter-active,
  .fade-leave-active {
    transition: opacity 0.5s;
  }
  .fade-enter, .fade-leave-to {
    opacity: 0;
  }
  </style>
  