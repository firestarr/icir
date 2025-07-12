<template>
  <div class="inspection-view">
    <div class="page-header">
      <div class="header-left">
        <h1>Inspection Details</h1>
        <div v-if="inspection" class="inspection-id">
          IC No: {{ inspection.ic_no || '-' }}
        </div>
      </div>
      <div class="header-actions">
        <button @click="printInspection" class="btn btn-success">
          🖨️ Print PDF
        </button>
        <router-link 
          v-if="inspection"
          :to="`/inspections/${inspection.id}/edit`" 
          class="btn btn-warning"
        >
          ✏️ Edit
        </router-link>
        <router-link to="/inspections" class="btn btn-secondary">
          ← Back to List
        </router-link>
      </div>
    </div>

    <div class="page-content">
      <div v-if="loading" class="loading">
        <div class="spinner"></div>
        <p>Loading inspection details...</p>
      </div>
      
      <div v-else-if="error" class="error">
        <h3>Error Loading Inspection</h3>
        <p>{{ error }}</p>
        <button @click="retryLoad" class="btn btn-primary">Retry</button>
      </div>
      
      <div v-else-if="inspection" class="inspection-details">
        <!-- Status Badge -->
        <div class="status-section">
          <span :class="getStatusClass(inspection.status)" class="status-badge">
            {{ getStatusLabel(inspection.status) }}
          </span>
          <span class="created-date">
            Created: {{ formatDate(inspection.created_at) }}
          </span>
        </div>

        <!-- Header Information Display -->
        <div class="info-section">
          <h2>📋 Basic Information</h2>
          <div class="info-grid">
            <div class="info-item">
              <label>Material:</label>
              <span>{{ inspection.material || '-' }}</span>
            </div>
            <div class="info-item">
              <label>PO No.:</label>
              <span>{{ inspection.po_no || '-' }}</span>
            </div>
            <div class="info-item">
              <label>Sample Size:</label>
              <span>{{ inspection.sample_size || '-' }}</span>
            </div>
            <div class="info-item">
              <label>Supplier:</label>
              <span>{{ inspection.supplier || '-' }}</span>
            </div>
            <div class="info-item">
              <label>Issue Date:</label>
              <span>{{ formatDate(inspection.issue_date) }}</span>
            </div>
            <div class="info-item">
              <label>Instrument Used:</label>
              <span>{{ inspection.instrument_used || '-' }}</span>
            </div>
            <div class="info-item">
              <label>Description:</label>
              <span>{{ inspection.description || '-' }}</span>
            </div>
            <div class="info-item">
              <label>Location:</label>
              <span>{{ inspection.location || '-' }}</span>
            </div>
            <div class="info-item">
              <label>IC No.:</label>
              <span>{{ inspection.ic_no || '-' }}</span>
            </div>
            <div class="info-item">
              <label>ACC/REJECT:</label>
              <span :class="getAccRejectClass(inspection.acc_reject)">
                {{ inspection.acc_reject || '-' }}
              </span>
            </div>
            <div class="info-item">
              <label>AQL:</label>
              <span>{{ inspection.aql || '-' }}</span>
            </div>
            <div class="info-item">
              <label>Supplier Lot No.:</label>
              <span>{{ inspection.supplier_lot_no || '-' }}</span>
            </div>
          </div>
        </div>

        <!-- Dimension Data Display -->
        <div v-if="inspection.dimensions && inspection.dimensions.length" class="dimension-section">
          <h2>📏 Dimension Inspection Results</h2>
          <div class="table-container">
            <table class="dimension-table">
              <thead>
                <tr>
                  <th>Type</th>
                  <th>Specification</th>
                  <th>Tolerance</th>
                  <th v-for="i in 10" :key="i">{{ i }}</th>
                  <th>Average</th>
                  <th>Remark</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="dimension in inspection.dimensions" :key="dimension.id">
                  <td class="dimension-type">{{ getDimensionLabel(dimension.dimension_type) }}</td>
                  <td>{{ dimension.specification || '-' }}</td>
                  <td>{{ dimension.tolerance || '-' }}</td>
                  <td v-for="i in 10" :key="i" class="sample-data">
                    {{ dimension[`sample_${i}`] || '-' }}
                  </td>
                  <td class="average-cell">
                    {{ dimension.rata_rata || calculateAverage(dimension) }}
                  </td>
                  <td>{{ dimension.remark || '-' }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Visual Check Display -->
        <div v-if="inspection.visual_checks && inspection.visual_checks.length" class="visual-check-section">
          <h2>👁️ Visual Check Results</h2>
          <div class="visual-checks-grid">
            <div v-for="check in inspection.visual_checks" :key="check.id" class="check-item">
              <div class="check-type">{{ check.check_type }}</div>
              <div class="check-status">
                <span v-if="check.accepted" class="status-accepted">✅ ACCEPTED</span>
                <span v-else-if="check.rejected" class="status-rejected">❌ REJECTED</span>
                <span v-else class="status-pending">⏳ PENDING</span>
              </div>
              <div v-if="check.remark" class="check-remark">{{ check.remark }}</div>
            </div>
          </div>
        </div>

        <!-- Signatures Display -->
        <div class="signatures-section">
          <h2>✍️ Approval Signatures</h2>
          <div class="signatures-display">
            <div class="signature-item">
              <h4>Inspected By</h4>
              <div v-if="getSignature('inspector')" class="signature-content">
                <img :src="getSignature('inspector').signature_data" alt="Inspector Signature" />
                <p class="signer-name">{{ getSignature('inspector').user_name }}</p>
                <p class="sign-date">{{ formatDate(getSignature('inspector').signed_at) }}</p>
              </div>
              <div v-else class="no-signature">Not signed</div>
            </div>

            <div class="signature-item">
              <h4>Checked By</h4>
              <div v-if="getSignature('checker')" class="signature-content">
                <img :src="getSignature('checker').signature_data" alt="Checker Signature" />
                <p class="signer-name">{{ getSignature('checker').user_name }}</p>
                <p class="sign-date">{{ formatDate(getSignature('checker').signed_at) }}</p>
              </div>
              <div v-else class="no-signature">Not signed</div>
            </div>

            <div class="signature-item">
              <h4>Approved By</h4>
              <div v-if="getSignature('approver')" class="signature-content">
                <img :src="getSignature('approver').signature_data" alt="Approver Signature" />
                <p class="signer-name">{{ getSignature('approver').user_name }}</p>
                <p class="sign-date">{{ formatDate(getSignature('approver').signed_at) }}</p>
              </div>
              <div v-else class="no-signature">Not signed</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { useInspectionStore } from '../stores/inspection'
import { storeToRefs } from 'pinia'

const route = useRoute()
const inspectionStore = useInspectionStore()

const { currentInspection: inspection, loading, error } = storeToRefs(inspectionStore)

const dimensionLabels = {
  thickness: 'A.1 THICKNESS',
  width: 'A.2 WIDTH', 
  length: 'A.3 LENGTH',
  diameter: 'A.4 DIAMETER',
  other: 'A.5 OTHER'
}

const getDimensionLabel = (type) => {
  return dimensionLabels[type] || type.toUpperCase()
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
  return `status-${status}`
}

const getAccRejectClass = (status) => {
  return status === 'ACC' ? 'acc-status' : status === 'REJECT' ? 'reject-status' : ''
}

const getSignature = (role) => {
  return inspection.value?.signatures?.find(sig => sig.role === role)
}

const calculateAverage = (dimension) => {
  const samples = []
  for (let i = 1; i <= 10; i++) {
    const value = dimension[`sample_${i}`]
    if (value !== null && value !== undefined && value !== '') {
      samples.push(parseFloat(value))
    }
  }
  
  if (samples.length === 0) return ''
  const average = samples.reduce((sum, val) => sum + val, 0) / samples.length
  return average.toFixed(3)
}

const formatDate = (dateString) => {
  if (!dateString) return '-'
  return new Date(dateString).toLocaleDateString('id-ID', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

const printInspection = () => {
  if (inspection.value?.id) {
    window.open(`/api/v1/inspections/${inspection.value.id}/pdf`, '_blank')
  }
}

const retryLoad = () => {
  inspectionStore.fetchInspection(route.params.id)
}

onMounted(() => {
  inspectionStore.fetchInspection(route.params.id)
})
</script>

<style scoped>
.inspection-view {
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

.inspection-id {
  color: #6c757d;
  font-size: 0.9rem;
}

.header-actions {
  display: flex;
  gap: 1rem;
}

.page-content {
  padding: 2rem;
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
}

.btn-success { background-color: #28a745; color: white; }
.btn-warning { background-color: #ffc107; color: #212529; }
.btn-secondary { background-color: #6c757d; color: white; }
.btn-primary { background-color: #007bff; color: white; }

.btn:hover {
  opacity: 0.9;
  transform: translateY(-1px);
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

.status-section {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 2rem;
  padding: 1rem;
  background: white;
  border-radius: 0.5rem;
  border: 1px solid #dee2e6;
}

.status-badge {
  padding: 0.5rem 1rem;
  border-radius: 20px;
  font-weight: bold;
  font-size: 0.9rem;
}

.status-draft { background-color: #6c757d; color: white; }
.status-inspected { background-color: #ffc107; color: #212529; }
.status-checked { background-color: #fd7e14; color: white; }
.status-approved { background-color: #28a745; color: white; }

.created-date {
  color: #6c757d;
  font-size: 0.9rem;
}

.info-section, .dimension-section, .visual-check-section, .signatures-section {
  margin-bottom: 2rem;
  background: white;
  border: 1px solid #dee2e6;
  border-radius: 0.5rem;
  overflow: hidden;
}

.info-section h2, .dimension-section h2, .visual-check-section h2, .signatures-section h2 {
  margin: 0;
  padding: 1rem;
  background: #f8f9fa;
  border-bottom: 1px solid #dee2e6;
  color: #495057;
  font-size: 1.25rem;
}

.info-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 1rem;
  padding: 1rem;
}

.info-item {
  display: flex;
  flex-direction: column;
}

.info-item label {
  font-weight: 600;
  color: #495057;
  margin-bottom: 0.25rem;
  font-size: 0.9rem;
}

.info-item span {
  color: #212529;
  padding: 0.5rem;
  background-color: #f8f9fa;
  border-radius: 0.25rem;
  border: 1px solid #e9ecef;
}

.acc-status {
  background-color: #d4edda !important;
  color: #155724 !important;
  font-weight: bold;
}

.reject-status {
  background-color: #f8d7da !important;
  color: #721c24 !important;
  font-weight: bold;
}

.table-container {
  overflow-x: auto;
  padding: 1rem;
}

.dimension-table {
  width: 100%;
  border-collapse: collapse;
  font-size: 0.85rem;
}

.dimension-table th,
.dimension-table td {
  border: 1px solid #dee2e6;
  padding: 0.5rem;
  text-align: center;
  white-space: nowrap;
}

.dimension-table th {
  background-color: #e9ecef;
  font-weight: 600;
  color: #495057;
}

.dimension-type {
  font-weight: 600;
  text-align: left;
}

.sample-data {
  font-family: monospace;
}

.average-cell {
  background-color: #e7f3ff;
  font-weight: bold;
  color: #0066cc;
}

.visual-checks-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 1rem;
  padding: 1rem;
}

.check-item {
  border: 1px solid #dee2e6;
  border-radius: 0.25rem;
  padding: 1rem;
  background-color: #fafafa;
}

.check-type {
  font-weight: 600;
  margin-bottom: 0.5rem;
  color: #495057;
}

.check-status {
  margin-bottom: 0.5rem;
}

.status-accepted {
  color: #28a745;
  font-weight: bold;
}

.status-rejected {
  color: #dc3545;
  font-weight: bold;
}

.status-pending {
  color: #6c757d;
}

.check-remark {
  font-style: italic;
  color: #6c757d;
  font-size: 0.9rem;
}

.signatures-display {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 1rem;
  padding: 1rem;
}

.signature-item {
  text-align: center;
  border: 1px solid #dee2e6;
  border-radius: 0.25rem;
  padding: 1rem;
  background-color: #fafafa;
}

.signature-item h4 {
  margin-bottom: 1rem;
  color: #495057;
}

.signature-content img {
  max-width: 200px;
  max-height: 100px;
  border: 1px solid #dee2e6;
  border-radius: 0.25rem;
  margin-bottom: 0.5rem;
  background-color: white;
}

.signer-name {
  font-weight: bold;
  margin: 0.5rem 0;
  color: #495057;
}

.sign-date {
  font-size: 0.8rem;
  color: #6c757d;
  margin: 0;
}

.no-signature {
  color: #6c757d;
  font-style: italic;
  padding: 2rem;
  border: 2px dashed #dee2e6;
  border-radius: 0.25rem;
}

@media (max-width: 768px) {
  .page-header {
    flex-direction: column;
    gap: 1rem;
    text-align: center;
  }
  
  .header-actions {
    flex-direction: column;
    width: 100%;
  }
  
  .page-content {
    padding: 1rem;
  }
  
  .info-grid {
    grid-template-columns: 1fr;
  }
  
  .signatures-display {
    grid-template-columns: 1fr;
  }
  
  .status-section {
    flex-direction: column;
    gap: 1rem;
    text-align: center;
  }
}
</style>