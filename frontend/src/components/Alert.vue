<template>
  <div
    v-if="visible"
    :class="['alert', alertClass]"
    role="alert"
    class="floating-alert fade show"
  >
    {{ message }}
  </div>
</template>

<script setup lang="ts">
import { ref, watch, defineProps } from 'vue';

enum ETypes {
  success,
  error
}

const props = defineProps<{
  visible: boolean;
  type: ETypes;
  message: string;
  code?: string | number
}>();

const alertClass = ref('alert-success')
const visible = ref(props.visible);

watch(() => props.code, (val) => {
  if (props.visible) {
    visible.value = props.visible;
    alertClass.value = props.type === ETypes.success ? 'alert-success' : 'alert-danger'
    setTimeout(() => {
      visible.value = false;
      alertClass.value =''
    }, 5000);
  }
});

</script>

<style scoped>
  .floating-alert {
    position: fixed;
    top: 1rem;
    right: 1rem;
    min-width: 250px;
    z-index: 1100; /* acima de modals e outros */
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    border-radius: 0.375rem;
  }
</style>