import axios from 'axios'

const api = axios.create({
  baseURL: '/api/v1',
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  }
})

// Request interceptor untuk menambahkan authorization header jika diperlukan
api.interceptors.request.use(
  (config) => {
    // Add auth token if available
    const token = localStorage.getItem('authToken')
    if (token) {
      config.headers.Authorization = `Bearer ${token}`
    }
    return config
  },
  (error) => {
    return Promise.reject(error)
  }
)

// Response interceptor untuk handle errors
api.interceptors.response.use(
  (response) => {
    return response
  },
  (error) => {
    if (error.response) {
      // Server responded with error status
      console.error('API Error:', error.response.data)
    } else if (error.request) {
      // Request was made but no response received
      console.error('Network Error:', error.request)
    } else {
      // Something else happened
      console.error('Error:', error.message)
    }
    return Promise.reject(error)
  }
)

export const inspectionApi = {
  // Get all inspections
  getAll() {
    return api.get('/inspections')
  },

  // Get single inspection
  getById(id) {
    return api.get(`/inspections/${id}`)
  },

  // Create new inspection
  create(data) {
    return api.post('/inspections', data)
  },

  // Update inspection
  update(id, data) {
    return api.put(`/inspections/${id}`, data)
  },

  // Delete inspection
  delete(id) {
    return api.delete(`/inspections/${id}`)
  },

  // Add signature
  addSignature(id, signatureData) {
    return api.post(`/inspections/${id}/signature`, signatureData)
  },

  // Generate PDF
  generatePdf(id) {
    return api.get(`/inspections/${id}/pdf`, {
      responseType: 'blob'
    })
  }
}

export default api
