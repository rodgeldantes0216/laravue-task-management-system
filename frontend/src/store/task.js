import { defineStore } from 'pinia'
import api from '@/utils/axios'

export const useTaskStore = defineStore('task', {
  state: () => ({
    tasks: [],
  }),
  actions: {
    async fetchTasks(params = {}) {
      const res = await api.get('/api/tasks', { params })
      this.tasks = res.data
    },
    async createTask(task) {
      const res = await api.post('/api/tasks', task)
      this.tasks.push(res.data)
    },
    async updateTask(id, task) {
      const res = await api.put(`/api/tasks/${id}`, task)
      this.tasks = this.tasks.map(t => t.id === id ? res.data : t)
    },
    async deleteTask(id) {
      await api.delete(`/api/tasks/${id}`)
      this.tasks = this.tasks.filter(t => t.id !== id)
    },
    async reorderTasks(newOrder) {
      await api.post('/api/tasks/reorder', { tasks: newOrder })
    },
  }
})
