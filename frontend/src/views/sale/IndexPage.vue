<template>
  <div class="container mt-4">
    <div class="d-flex justify-content-between mb-4">
      <div><h3>Vendas</h3></div>
      <div><button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#saleFormModal" @click="clearFormData()">Nova Venda</button></div>
    </div>

    <div class="row">
      <div class="col-md-4 mb-4">
        <label for="seller_filter">Busque por vendedor</label>
        <select v-model="filter.seller_id" @change="getSalesBySeller()" class="form-select" id="seller_filter">
          <option value="">Todos os vendedores</option>
          <option v-for="s in sellers" :key="s.id" :value="s.id">{{ s.name }}</option>
        </select>
      </div>
    </div>

    <Alert
      :visible="alert.visible"
      :type="alert.type"
      :message="alert.message"
      :code="alert.code"
    />

    <table class="table table-bordered ">
      <tbody>
        <tr class="show-seller" v-for="sale in sales" :key="sale.id">
          <td>
            {{ sale.description }}
          </td>
          <td>
            {{ currencyFormatBRL(sale.amount) }}
          </td>
          <td>
            Vendido por {{ sale.seller.name }}
          </td>
          <td>
            {{ dateFormatBRL(sale.sale_date) }}
          </td>
          <td>
            <button class="btn btn-danger rounded-circle m-1" title="Deletar" @click="showConfirmDeletionModal(sale)">
              <i class="bi bi-trash3 text-white"></i>
            </button>
            <button class="btn btn-primary rounded-circle m-1" title="Editar" data-bs-toggle="modal" data-bs-target="#saleFormModal" @click="fillSaleFormModal(sale)">
              <i class="bi bi-pencil text-white"></i>
            </button>
          </td>
        </tr>
        <tr v-if="!sales.length">
          <td class="text-center text-body-secondary">
            <h4>Nenhuma venda registrada</h4>
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
    <div class="modal fade" id="saleFormModal" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <form @submit.prevent="submitSale">
            <div class="modal-header">
              <h5 class="modal-title">Cadastrar Venda</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
              <div class="mb-3">
                <input v-model="form.description" type="text" class="form-control" placeholder="Descrição">
              </div>
              <div class="mb-3">
                <select v-model="form.seller_id" class="form-select">
                  <option value="">Selecione o vendedor</option>
                  <option v-for="s in sellers" :key="s.id" :value="s.id">{{ s.name }}</option>
                </select>
              </div>
              <div class="mb-3">
                <input
                  type="text"
                  class="form-control"
                  :value="formattedAmount"
                  @input="formatAmount"
                  placeholder="R$ 0,00"
                />
              </div>
              <div class="mb-3">
                <input v-model="form.sale_date" type="date" class="form-control">
              </div>
              <div class="text-danger" v-if="errors">
                <div v-for="(msg, field) in errors" :key="field">
                  {{ msg[0] }}
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
              <button class="btn btn-success" type="submit">Salvar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { currencyFormatBRL } from '@/utils/currency';
import { ref, onMounted, computed } from 'vue'
import * as bootstrap from 'bootstrap'
import axios from 'axios'
import Alert from '@/components/Alert.vue';
import { ISeller } from '@/types/SellerTypes';
import { IFormSale, ISale, ISalesFilter } from '@/types/SaleTypes';
import { useAlert } from '@/composables/useAlert'
import ConfirmDeleteModal from '@/components/ConfirmDeleteModal.vue'
import { dateFormatBRL } from '@/utils/dates';

const sales = ref([])
const sellers = ref<ISeller[]>([])
const saleToDelete = ref<ISale>({})
const form = ref<IFormSale>({})
const filter = ref<ISalesFilter>({ seller_id: '' })
const errors = ref(null)
const { alert, alertSuccess, alertError } = useAlert()
const confirmDeletionModal = ref({ show: false, itemDescription: '' })
const formattedAmount = computed(() => {
  return currencyFormatBRL(form.value.amount || 0);
});

const formatAmount = (event: Event) => {
  const raw = (event.target as HTMLInputElement).value;
  const digits = raw.replace(/\D/g, '');
  const numericValue = parseFloat(digits) / 100;

  form.value.amount = numericValue
}

const getSales = async () => {
  const { data } = await axios.get('/api/sales')
  sales.value = data
}

const getSalesBySeller = async () => {
  if(!filter.value.seller_id) {
    getSales()
    return
  }

  const { data } = await axios.get(`/api/sellers/${filter.value.seller_id}/sales`)
  sales.value = data
}

const getSellers = async () => {
  const { data } = await axios.get('/api/sellers')
  sellers.value = data
}

const submitSale = () => {
  if(form.value.id) {
    updateSale()
    return;
  }

  createSale()
}

const clearFormData = () => {
  form.value = {}
  errors.value = null
}

const createSale = async () => {
  try {
    await axios.post('/api/sales', form.value)
    clearFormData()
    getSales()
    alertSuccess('Venda registrada com sucesso')
    bootstrap.Modal.getInstance(document.getElementById('saleFormModal')).hide()
  } catch (e) {
    if (e.response.status === 422) {
      errors.value = e.response.data.errors
    }

    alertError('Não foi possível registrar a venda')
  }
}

const updateSale = async () => {
  try {
    await axios.put('/api/sales/'+ form.value.id, form.value)
    clearFormData()
    getSales()
    alertSuccess('Venda atualizada com sucesso')
    bootstrap.Modal.getInstance(document.getElementById('saleFormModal')).hide()
  } catch (e) {
    if (e.response.status === 422) {
      errors.value = e.response.data.errors
    }

    alertError('Não foi possível atualizar a venda')
  }
}

const fillSaleFormModal = (sale) => {
  clearFormData()
  form.value = { id: sale.id, description: sale.description, seller_id: sale.seller_id, amount: sale.amount, sale_date: sale.sale_date }
}

const showConfirmDeletionModal = (sale: ISale) => {
  confirmDeletionModal.value = {show: true, itemDescription: sale.description}
  saleToDelete.value = sale
}

const confirmDeletion = async () => {
  try {
    const response = await axios.delete('/api/sales/'+ saleToDelete.value.id)

    if(response.error) {
      alertError(response.error)
      errors.value = response.error
      return;
    }

    getSales()
    alertSuccess('Venda deletada com sucesso')
    resetDeletion()
  } catch (e) {
    alertError('Não foi possível deletar a venda')
  }
}

const resetDeletion = async () => {
  confirmDeletionModal.value = {show: false, itemDescription: ''}
  saleToDelete.value = null
}

onMounted(() => {
  getSales()
  getSellers()
})

</script>