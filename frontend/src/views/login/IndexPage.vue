<template>
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-12">
        <div class="card shadow">
          <div class="card-body">
            <h3 class="card-title text-center mb-4">Login</h3>
            <form @submit.prevent="login">
              <div class="form-group mb-3">
                <label>Email</label>
                <input v-model="email" type="email" class="form-control" required />
              </div>
              <div class="form-group mb-4">
                <label>Senha</label>
                <input v-model="password" type="password" class="form-control" required />
              </div>
              <button class="btn btn-primary w-100" type="submit">Entrar</button>
            </form>
            <div v-if="error" class="alert alert-danger mt-3">{{ error }}</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/auth';
import axios from '@/axios';

const email = ref('admin@admin.com');
const password = ref('123123');
const error = ref('');
const router = useRouter();
const auth = useAuthStore();

const login = async () => {
  error.value = '';
  try {
    const response = await axios.post('/api/login', { email: email.value, password: password.value });
    localStorage.setItem('auth_token', response.data.access_token);
    auth.setUser(response.data);
    router.push('/sellers');
  } catch(error) {
    error.value = 'Email ou senha inválidos.';
  }
};

const validateSession = () => {
  if(auth.isAuthenticated) {
    router.push('/sellers');
  }
}

onMounted(() => {
  validateSession()
})

</script>