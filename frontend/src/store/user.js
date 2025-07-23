import { defineStore } from 'pinia'
import api from '@/utils/axios'

export const useUserStore = defineStore('user', {
  state: () => ({
    user: null,
    isAuthenticated: false,
  }),

  actions: {
    async login(form) {
      try {
        const res = await api.post('/api/login', form)

        const { user, token } = res.data
        localStorage.setItem('auth_token', token)
        api.defaults.headers.common['Authorization'] = `Bearer ${token}`

        this.user = user
        this.isAuthenticated = true

        api.defaults.headers.Authorization = `Bearer ${token}`

        console.log('✅ Login successful')
      } catch (err) {
        console.error('❌ Login failed:', err.response?.data)
        this.user = null
        this.isAuthenticated = false
      }
    },

    async logout() {
      try {
        await api.post('/api/logout')
        localStorage.removeItem('auth_token')
        delete api.defaults.headers.common['Authorization']

        this.user = null
        this.isAuthenticated = false
      } catch (err) {
        console.error('❌ Logout failed:', err.response?.data)
      }
    },

    async fetchProfile() {
      try {
        const res = await api.get('/api/me')
        this.user = res.data
        this.isAuthenticated = true
      } catch (err) {
        console.error('❌ Fetch profile failed:', err.response?.data)
        this.user = null
        this.isAuthenticated = false
      }
    }
  }
})
