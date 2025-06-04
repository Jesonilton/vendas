<template>
  <div
    class="modal fade"
    id="confirmDeleteModal"
    tabindex="-1"
    ref="modalRef"
    aria-labelledby="confirmDeleteLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="confirmDeleteLabel">Atenção</h5>
          <button type="button" class="btn-close" @click="cancel" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          Tem certeza que deseja deletar <strong>{{ itemDescription }}</strong>?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" @click="cancel" data-bs-dismiss="modal">
            Não
          </button>
          <button type="button" class="btn btn-danger" @click="confirmDeletion">
            Sim
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, watch } from 'vue'
import { Modal } from 'bootstrap'

const props = defineProps<{
  itemDescription: string
  show: boolean
}>()

const emit = defineEmits<{
  (e: 'confirm'): void
  (e: 'cancel'): void
}>()

const modalRef = ref<HTMLElement | null>(null)
let modalInstance: Modal | null = null

onMounted(() => {
  if (modalRef.value) {
    modalInstance = new Modal(modalRef.value, { backdrop: 'static' })

    watch(() => props.show, (newVal) => {
        if (newVal) {
            modalInstance?.show()
            return
        }
        
        modalInstance?.hide()
      }
    )

    modalRef.value.addEventListener('hidden.bs.modal', () => {
      emit('cancel')
    })
  }
})

function confirmDeletion() {
  emit('confirm')
}

function cancel() {
  emit('cancel')
}
</script>