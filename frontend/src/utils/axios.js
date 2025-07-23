import axios from 'axios'

const token = localStorage.getItem('auth_token')

const api = axios.create({
  baseURL: 'http://localhost:8000',
  withCredentials: true,
  headers: {
    Accept: 'application/json',
    Authorization: `Bearer ${token}`,
  }
})

export default api