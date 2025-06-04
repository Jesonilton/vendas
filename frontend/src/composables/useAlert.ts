import { ref } from 'vue'

export interface IAlert {
  visible: boolean, 
  type: ETypes, 
  message: string
}

export enum ETypes {
  success,
  error
}

export function useAlert() {
  const alert = ref<IAlert>({
    visible: false,
    type: ETypes.success,
    message: ''
  })

  const alertSuccess = (message: string) => {
    alert.value = {
      visible: true,
      type: ETypes.success,
      message,
      code: Date.now().toString()
    }
  }

  const alertError = (message: string) => {
    alert.value = {
      visible: true,
      type: ETypes.error,
      message,
      code: Date.now().toString()
    }
  }

  return {
    alert,
    alertSuccess,
    alertError
  }
}