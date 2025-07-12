<template>
  <div class="inspection-list">
    <div class="page-header">
      <div class="header-left">
        <h1>Inspection Records</h1>
        <p class="subtitle">Manage incoming inspection records (ICIR)</p>
      </div>
      <router-link to="/inspections/create" class="btn btn-primary">
        ➕ Create New Inspection
      </router-link>
    </div>

    <div class="page-content">
      <div class="filters-section">
        <div class="search-box">
          <input 
            v-model="searchQuery" 
            type="text" 
            placeholder="Search by IC No, Material, Supplier..."
            class="search-input"
          />
        </div>
        <div class="filter-buttons">
          <button 
            v-for="status in statusFilters" 
            :key="status.value"
            @click="selectedStatus = status.value"
            :class="['filter-btn', { active: selectedStatus === status.value }]"
          >
            {{ status.label }}
          </button>
        </div>
      </div>

      <div v-if="loading" class="loading">
        <div class="spinner"></div>
        <p>Loading inspections...</p>
      </div>
      
      <div v-else-if="error" class="error">
        <h3>Error Loading Inspections</h3>
        <p>{{ error }}</p>
        <button @click="fetchInspections" class="btn btn-primary">Retry</button>
      </div>
      
      <div v-else class="table-section">
        <div class="table-header">
          <h3>{{ filteredInspections.length }} Record{{ filteredInspections.length !== 1 ? 's' : '' }}</h3>
        </div>
        
        <div class="table-container">
          <table class="inspection-table">
            <thead>
              <tr>
                <th>IC No.</th>
                <th>Material</th>
                <th>Supplier</th>
                <th>PO No.</th>
                <th>Issue Date</th>
                <th>Status</th>
                <th>ACC/REJ</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-if="filteredInspections.length === 0">
                <td colspan="8" class="no-data">
                  No inspections found. 
                  <router-link to="/inspections/create">Create your first inspection</router-link>
                </td>
              </tr>
              <tr v-for="inspection in filteredInspections" :key="inspection.id" class="table-row">
                <td class="ic-no">{{ inspection.ic_no || '-' }}</td>
                <td class="material">{{ inspection.material || '-' }}</td>
                <td class="supplier">{{ inspection.supplier || '-' }}</td>
                <td class="po-no">{{ inspection.po_no || '-' }}</td>
                <td class="issue-date">{{ formatDate(inspection.issue_date) }}</td>
                <td class="status">
                  <span :class="getStatusClass(inspection.status)">
                    {{ getStatusLabel(inspection.status) }}
                  </span>
                </td>
                <td class="acc-reject">
                  <span v-if="inspection.acc_reject" :class="getAccRejectClass(inspection.acc_reject)">
                    {{ inspection.acc_reject }}
                  </span>
                  <span v-else class="pending">-</span>
                </td>
                <td class="actions">
                  <div class="action-buttons">
                    <router-link 
                      :to="`/inspections/${inspection.id}/view`" 
                      class="btn btn-sm btn-info"
                      title="View Details"
                    >
                      👁️
                    </router-link>
                    <router-link 
                      :to="`/inspections/${inspection.id}/edit`" 
                      class="btn btn-sm btn-warning"
                      title="Edit"
                    >
                      ✏️
                    </router-link>
                    <button 
                      @click="confirmDelete(inspection)" 
                      class="btn btn-sm btn-danger"
                      title="Delete"
                    >
                      🗑️
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div v-if="showDeleteModal" class="modal-overlay" @click="showDeleteModal = false">
      <div class="modal-content" @click.stop>
        <h3>Confirm Delete</h3>
        <p>Are you sure you want to delete this inspection record?</p>
        <div class="modal-actions">
          <button @click="showDeleteModal = false" class="btn btn-secondary">Cancel</button>
          <button @click="deleteInspection" class="btn btn-danger">Delete</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted, computed, ref } from 'vue'
import { useInspectionStore } from '../stores/inspection'
import { storeToRefs } from 'pinia'

const inspectionStore = useInspectionStore()
const { inspections, loading, error } = storeToRefs(inspectionStore)

const searchQuery = ref('')
const selectedStatus = ref('all')
const showDeleteModal = ref(false)
const inspectionToDelete = ref(null)

const statusFilters = [
  { label: 'All', value: 'all' },
  { label: 'Draft', value: 'draft' },
  { label: 'Inspected', value: 'inspected' },
  { label: 'Checked', value: 'checked' },
  { label: 'Approved', value: 'approved' }
]

const filteredInspections = computed(() => {
  let filtered = inspections.value

  // Filter by search query
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase()
    filtered = filtered.filter(inspection => 
      (inspection.ic_no && inspection.ic_no.toLowerCase().includes(query)) ||
      (inspection.material && inspection.material.toLowerCase().includes(query)) ||
      (inspection.supplier && inspection.supplier.toLowerCase().includes(query)) ||
      (inspection.po_no && inspection.po_no.toLowerCase().includes(query))
    )
  }

  // Filter by status
  if (selectedStatus.value !== 'all') {
    filtered = filtered.filter(inspection => inspection.status === selectedStatus.value)
  }

  return filtered
})

const formatDate = (dateString) => {
  if (!dateString) return '-'
  return new Date(dateString).toLocaleDateString('id-ID', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  })
}

const getStatusLabel = (status) => {
  const labels = {
    draft: 'Draft',
    inspected: 'Inspected',
    checked: 'Checked',
    approved: 'Approved'
  }
  return labels[status] || status
}

const getStatusClass = (status) => {
  return `status status-${status}`
}

const getAccRejectClass = (status) => {
  return status === 'ACC' ? 'acc-status' : status === 'REJECT' ? 'reject-status' : ''
}

const confirmDelete = (inspection) => {
  inspectionToDelete.value = inspection
  showDeleteModal.value = true
}

const deleteInspection = async () => {
  if (inspectionToDelete.value) {
    try {
      await inspectionStore.deleteInspection(inspectionToDelete.value.id)
      showDeleteModal.value = false
      inspectionToDelete.value = null
    } catch (error) {
      alert('Failed to delete inspection')
    }
  }
}

onMounted(() => {
  inspectionStore.fetchInspections()
})
</script>

<style scoped>
.inspection-list {
  min-height: 100vh;
  background-color: #f8f9fa;
}

.page-header {
  background: white;
  padding: 2rem;
  border-bottom: 1px solid #dee2e6;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.header-left h1 {
  margin: 0 0 0.5rem 0;
  color: #495057;
}

.subtitle {
  color: #6c757d;
  margin: 0;
  font-size: 0.9rem;
}

.page-content {
  padding: 2rem;
}

.filters-section {
  background: white;
  padding: 1.5rem;
  border-radius: 0.5rem;
  border: 1px solid #dee2e6;
  margin-bottom: 2rem;
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.search-box {
  flex: 1;
}

.search-input {
  width: 100%;
  padding: 0.75rem;
  border: 1px solid #ced4da;
  border-radius: 0.375rem;
  font-size: 1rem;
}

.search-input:focus {
  outline: none;
  border-color: #80bdff;
  box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.filter-buttons {
  display: flex;
  gap: 0.5rem;
  flex-wrap: wrap;
}

.filter-btn {
  padding: 0.5rem 1rem;
  border: 1px solid #dee2e6;
  background: white;
  border-radius: 0.375rem;
  cursor: pointer;
  transition: all 0.2s;
}

.filter-btn:hover {
  background: #f8f9fa;
}

.filter-btn.active {
  background: #007bff;
  color: white;
  border-color: #007bff;
}

.btn {
  padding: 0.5rem 1rem;
  border: none;
  border-radius: 0.375rem;
  text-decoration: none;
  font-weight: 500;
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  cursor: pointer;
  transition: all 0.2s;
}

.btn-primary { background-color: #007bff; color: white; }
.btn-secondary { background-color: #6c757d; color: white; }
.btn-info { background-color: #17a2b8; color: white; }
.btn-warning { background-color: #ffc107; color: #212529; }
.btn-danger { background-color: #dc3545; color: white; }

.btn-sm {
  padding: 0.25rem 0.5rem;
  font-size: 0.875rem;
}

.loading {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 3rem;
  color: #6c757d;
}

.spinner {
  width: 40px;
  height: 40px;
  border: 4px solid #f3f3f3;
  border-top: 4px solid #007bff;
  border-radius: 50%;
  animation: spin 1s linear infinite;
  margin-bottom: 1rem;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

.error {
  text-align: center;
  padding: 3rem;
  color: #dc3545;
  background: white;
  border-radius: 0.5rem;
  border: 1px solid #f8d7da;
}

.table-section {
  background: white;
  border-radius: 0.5rem;
  border: 1px solid #dee2e6;
  overflow: hidden;
}

.table-header {
  padding: 1rem;
  background: #f8f9fa;
  border-bottom: 1px solid #dee2e6;
}

.table-header h3 {
  margin: 0;
  color: #495057;
}

.table-container {
  overflow-x: auto;
}

.inspection-table {
  width: 100%;
  border-collapse: collapse;
}

.inspection-table th,
.inspection-table td {
  padding: 1rem;
  text-align: left;
  border-bottom: 1px solid #dee2e6;
}

.inspection-table th {
  background-color: #f8f9fa;
  font-weight: 600;
  color: #495057;
  white-space: nowrap;
}

.table-row:hover {
  background-color: #f8f9fa;
}

.no-data {
  text-align: center;
  padding: 3rem;
  color: #6c757d;
}

.no-data a {
  color: #007bff;
  text-decoration: none;
}

.status {
  padding: 0.25rem 0.75rem;
  border-radius: 1rem;
  font-size: 0.75rem;
  font-weight: bold;
  text-transform: uppercase;
}

.status-draft { background-color: #6c757d; color: white; }
.status-inspected { background-color: #ffc107; color: #212529; }
.status-checked { background-color: #fd7e14; color: white; }
.status-approved { background-color: #28a745; color: white; }

.acc-status {
  background-color: #d4edda;
  color: #155724;
  padding: 0.25rem 0.5rem;
  border-radius: 0.25rem;
  font-weight: bold;
}

.reject-status {
  background-color: #f8d7da;
  color: #721c24;
  padding: 0.25rem 0.5rem;
  border-radius: 0.25rem;
  font-weight: bold;
}

.pending {
  color: #6c757d;
}

.action-buttons {
  display: flex;
  gap: 0.25rem;
}

.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
}

.modal-content {
  background: white;
  border-radius: 0.5rem;
  padding: 2rem;
  max-width: 400px;
  width: 90%;
}

.modal-content h3 {
  margin-top: 0;
  color: #495057;
}

.modal-actions {
  display: flex;
  gap: 1rem;
  justify-content: flex-end;
  margin-top: 1.5rem;
}

@media (max-width: 768px) {
  .page-header {
    flex-direction: column;
    gap: 1rem;
    text-align: center;
  }
  
  .page-content {
    padding: 1rem;
  }
  
  .filters-section {
    padding: 1rem;
  }
  
  .inspection-table th,
  .inspection-table td {
    padding: 0.5rem;
    font-size: 0.875rem;
  }
  
  .action-buttons {
    flex-direction: column;
  }
}
</style>