<template>
  <div>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <div class="container-fluid">
        <div class="d-flex align-items-center">
          <RouterLink to="/" class="nav-link navbar-brand fw-semibold">TrayVendas</RouterLink>
          <ul class="navbar-nav ms-3">
            <li class="nav-item me-2">
              <RouterLink to="/sellers" class="nav-link fw-semibold">Vendedores</RouterLink>
            </li>
            <li class="nav-item">
              <RouterLink to="/sales" class="nav-link fw-semibold">Vendas</RouterLink>
            </li>
          </ul>
        </div>

        <div>
          <button class="btn btn-outline-danger btn-sm" @click="logout">Sair</button>
        </div>
      </div>
    </nav>
    <RouterView />
  </div>
</template>

<script setup>
  import { RouterLink, RouterView } from 'vue-router'
  import { useRouter } from 'vue-router'
  import { useAuthStore } from '@/stores/auth'
  import axios from '@/axios'

  const router = useRouter()
  const auth = useAuthStore()

  const logout = async () => {
    try {
      await axios.post('/api/logout')

      auth.setUser(null)
      localStorage.removeItem('auth_token');
      router.push('/login')
    } catch (err) {
      console.error('Erro ao sair:', err)
    }
  }
</script>