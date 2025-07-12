import { defineStore } from 'pinia'
import { inspectionApi } from '../utils/api'

export const useInspectionStore = defineStore('inspection', {
  state: () => ({
    inspections: [],
    currentInspection: null,
    loading: false,
    error: null
  }),

  getters: {
    getInspectionById: (state) => (id) => {
      return state.inspections.find(inspection => inspection.id === parseInt(id))
    }
  },

  actions: {
    async fetchInspections() {
      this.loading = true
      try {
        const response = await inspectionApi.getAll()
        this.inspections = response.data
      } catch (error) {
        this.error = error.message
        console.error('Failed to fetch inspections:', error)
      } finally {
        this.loading = false
      }
    },

    async fetchInspection(id) {
      this.loading = true
      try {
        const response = await inspectionApi.getById(id)
        this.currentInspection = response.data
        return response.data
      } catch (error) {
        this.error = error.message
        console.error('Failed to fetch inspection:', error)
        throw error
      } finally {
        this.loading = false
      }
    },

    async createInspection(data) {
      this.loading = true
      try {
        const response = await inspectionApi.create(data)
        this.inspections.unshift(response.data.data)
        return response.data.data
      } catch (error) {
        this.error = error.message
        console.error('Failed to create inspection:', error)
        throw error
      } finally {
        this.loading = false
      }
    },

    async updateInspection(id, data) {
      this.loading = true
      try {
        const response = await inspectionApi.update(id, data)
        const index = this.inspections.findIndex(item => item.id === parseInt(id))
        if (index !== -1) {
          this.inspections[index] = response.data.data
        }
        this.currentInspection = response.data.data
        return response.data.data
      } catch (error) {
        this.error = error.message
        console.error('Failed to update inspection:', error)
        throw error
      } finally {
        this.loading = false
      }
    },

    async deleteInspection(id) {
      this.loading = true
      try {
        await inspectionApi.delete(id)
        this.inspections = this.inspections.filter(item => item.id !== parseInt(id))
      } catch (error) {
        this.error = error.message
        console.error('Failed to delete inspection:', error)
        throw error
      } finally {
        this.loading = false
      }
    },

    async addSignature(id, signatureData) {
      try {
        const response = await inspectionApi.addSignature(id, signatureData)
        if (this.currentInspection && this.currentInspection.id === parseInt(id)) {
          if (!this.currentInspection.signatures) {
            this.currentInspection.signatures = []
          }
          const existingIndex = this.currentInspection.signatures.findIndex(s => s.role === signatureData.role)
          if (existingIndex !== -1) {
            this.currentInspection.signatures[existingIndex] = response.data.data
          } else {
            this.currentInspection.signatures.push(response.data.data)
          }
        }
        return response.data.data
      } catch (error) {
        this.error = error.message
        console.error('Failed to add signature:', error)
        throw error
      }
    }
  }
})