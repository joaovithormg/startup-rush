import { defineStore } from 'pinia'
import api from '../services/api'

export const useStartupStore = defineStore('startup', {
  state: () => ({
    startups: [],
    loading: false,
    error: null
  }),
  
  actions: {
    async fetchStartups() {
      this.loading = true
      try {
        const response = await api.getStartups()
        this.startups = response.data
        this.error = null
      } catch (error) {
        this.error = error.message || 'Erro ao buscar startups'
        console.error('Error fetching startups:', error)
      } finally {
        this.loading = false
      }
    },
    
    async createStartup(startup) {
      this.loading = true
      try {
        const response = await api.createStartup(startup)
        await this.fetchStartups()
        return response.data
      } catch (error) {
        this.error = error.message || 'Erro ao criar startup'
        console.error('Error creating startup:', error)
        throw error
      } finally {
        this.loading = false
      }
    }
  }
})