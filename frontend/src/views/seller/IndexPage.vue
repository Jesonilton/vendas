<template>
  <div class="container mt-4">
    <div class="d-flex justify-content-between mb-4">
      <div><h3>Vendedores</h3></div>
      <div><button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#sellerModal" @click="clearFormData()">Novo Vendedor</button></div>
    </div>

    <Alert
      :visible="alert.visible"
      :type="alert.type"
      :message="alert.message"
      :code="alert.code"
    />

    <table class="table table-bordered ">
      <tbody>
        <tr class="show-seller" v-for="seller in sellers" :key="seller.id">
          <td>
            {{ seller.name }} ({{ seller.email }})
          </td>
          <td>
            {{ seller.sales_count }} {{ seller.sales_count == 1? 'venda realizada': 'vendas realizadas' }} 
          </td>
          <td>
            {{ currencyFormatBRL(seller.sales_amount) }} em vendas
          </td>
          <td>
            {{ currencyFormatBRL(seller.commissions_amount) }} em comissões
          </td>
          <td>
            <button class="btn btn-danger rounded-circle m-1" title="Deletar" @click="showConfirmDeletionModal(seller)">
              <i class="bi bi-trash3 text-white"></i>
            </button>
            <button class="btn btn-primary rounded-circle m-1" title="Editar" data-bs-toggle="modal" data-bs-target="#sellerModal" @click="fillSellerModal(seller)">
              <i class="bi bi-pencil text-white"></i>
            </button>
            <button class="btn btn-warning rounded-circle" title="Enviar extrato de comissões" @click="sendCommissionEmail(seller)">
              <i class="bi bi-envelope-arrow-up  text-white"></i>
            </button>
          </td>
        </tr>
        <tr v-if="!sellers.length">
          <td class="text-center text-body-secondary">
            <h4>Nenhum vendedor registrado</h4>
          </td>
        </tr>
      </tbody>
    </table>

    <ConfirmDeleteModal
      v-model:show="confirmDeletionModal.show"
      :item-description="confirmDeletionModal.itemDescription"
      @confirm="confirmDeletion"
      @cancel="resetDeletion"
    />

    <!-- Modal -->
    <div class="modal fade" id="sellerModal" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <form @submit.prevent="submitSeller">
            <div class="modal-header">
              <h5 class="modal-title">Cadastrar Vendedor</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
              <div class="mb-3">
                <input v-model="form.name" class="form-control" placeholder="Nome">
              </div>
              <div class="mb-3">
                <input v-model="form.email" class="form-control" placeholder="Email">
              </div>
              <div class="text-danger" v-if="errors">
                <div v-for="(msg, field) in errors" :key="field">
                  {{ msg[0] }}
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button class="btn btn-secondary" type="button" data-bs-dismiss="modal" @click="clearFormData()">Fechar</button>
              <button class="btn btn-primary">Salvar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import axios from '@/axios'
import * as bootstrap from 'bootstrap'
import { currencyFormatBRL } from '@/utils/currency';
import Alert from '@/components/Alert.vue';
import { IFormSeller, ISeller } from '@/types/SellerTypes';
import { useAlert } from '@/composables/useAlert'
import ConfirmDeleteModal from '@/components/ConfirmDeleteModal.vue'

const { alert, alertSuccess, alertError } = useAlert()
const sellers = ref([])
const form = ref<IFormSeller>({ name: '', email: '' })
const sellerToDelete = ref<ISeller | null>(null)
const errors = ref(null)
const deletionError = ref<string>('')
const confirmDeletionModal = ref({ show: false, itemDescription: '' })

const getSellers = async () => {
  const { data } = await axios.get('/api/sellers')
  sellers.value = data
}

const clearFormData = () => {
  form.value = { id: undefined, name: '', email: '' };
  errors.value = null
}

const submitSeller = () => {
  if(form.value.id) {
    updateSeller()
    return;
  }

  createSeller()
}

const createSeller = async () => {
  try {
    await axios.post('/api/sellers', form.value)
    clearFormData()
    getSellers()
    alertSuccess('Vendedor registrado com sucesso')
    bootstrap.Modal.getInstance(document.getElementById('sellerModal')).hide()
  } catch (e) {
    if (e.response.status === 422) {
      errors.value = e.response.data.errors
    }

    alertError('Não foi possível registrar o vendedor')
  }
}

const updateSeller = async () => {
  try {
    await axios.put('/api/sellers/'+ form.value.id, form.value)
    clearFormData()
    getSellers()
    alertSuccess('Vendedor atualizado com sucesso')
    bootstrap.Modal.getInstance(document.getElementById('sellerModal')).hide()
  } catch (e) {
    if (e.response.status === 422) {
      errors.value = e.response.data.errors
    }
    alertError('Não foi possível atualizar o vendedor')
  }
}

const sendCommissionEmail = async (seller: ISeller) => {
  try {
    await axios.post(`api/sellers/${seller.id}/send-commission-email`)
    alertSuccess('Email enviado com sucesso')

  } catch (e) {
    alertError('Não foi possível enviar o email de comissões')
  }
}

const fillSellerModal = (seller: ISeller) => {
  form.value = { id: seller.id, name: seller.name, email: seller.email }
}

const showConfirmDeletionModal = (seller) => {
  confirmDeletionModal.value = {show: true, itemDescription: seller.name}
  sellerToDelete.value = seller
}

const confirmDeletion = async () => {
  try {
    const response = await axios.delete('/api/sellers/'+ sellerToDelete.value.id)

    if(response.data.success) {
      getSellers()
      alertSuccess('Vendedor deletado com sucesso')
      resetDeletion()
      return
    }
    
    deletionError.value = response.data.error
    alertError(response.data.error)
  } catch (e) {
    alertError('Não foi possível deletar o vendedor')
  }
}

const resetDeletion = async () => {
  confirmDeletionModal.value = {show: false, itemDescription: ''}
  sellerToDelete.value = null
}

onMounted(getSellers)
</script>

<style scoped>
  .show-seller div {
    border-right: 1px solid red;
  }
</style>

