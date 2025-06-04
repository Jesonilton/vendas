
import axios from '@/axios';
import { defineStore } from 'pinia';

interface IUser {
  id: number;
  name: string;
  email: string;
}

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null as IUser | null,
  }),

  getters: {
    isAuthenticated: (state): boolean => !!state.user,
  },

  actions: {
    setUser(user: IUser) {
       this.user = user
    },
    async refreshUser() {
      const token = localStorage.getItem('auth_token');

      if (token && this.user !== null) {
        //nothing to do
        return true;
      }

      if (!token) {
        this.user = null;
        return true;
      }

      axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;

      try {
        const response = await axios.get('/api/auth/user');
        this.user = response.data;
      } catch (e) {
        localStorage.removeItem('auth_token');
        delete axios.defaults.headers.common['Authorization'];
        this.user = null;
      }

      return true;
    }
  }
});