<template>
  <div class="signature-section">
    <div class="section-header">
      <h3>✍️ APPROVAL SIGNATURES</h3>
      <p class="section-subtitle">Digital signatures for approval workflow</p>
    </div>
    
    <div class="signature-content">
      <div class="signatures-grid">
        <div class="signature-card">
          <div class="signature-header">
            <h4>Inspected By</h4>
            <span class="role-badge inspector">Inspector</span>
          </div>
          
          <div v-if="existingSignatures.inspector" class="existing-signature">
            <div class="signature-image">
              <img :src="existingSignatures.inspector.signature_data" alt="Inspector Signature" />
            </div>
            <div class="signature-info">
              <p class="signer-name">{{ existingSignatures.inspector.user_name }}</p>
              <p class="sign-date">{{ formatDate(existingSignatures.inspector.signed_at) }}</p>
            </div>
            <button @click="removeSignature('inspector')" class="btn btn-danger btn-sm">
              🗑️ Remove Signature
            </button>
          </div>
          
          <div v-else class="signature-input">
            <DigitalSignature 
              role="inspector" 
              @signature-saved="handleSignatureSaved"
            />
          </div>
        </div>

        <div class="signature-card">
          <div class="signature-header">
            <h4>Checked By</h4>
            <span class="role-badge checker">Checker</span>
          </div>
          
          <div v-if="existingSignatures.checker" class="existing-signature">
            <div class="signature-image">
              <img :src="existingSignatures.checker.signature_data" alt="Checker Signature" />
            </div>
            <div class="signature-info">
              <p class="signer-name">{{ existingSignatures.checker.user_name }}</p>
              <p class="sign-date">{{ formatDate(existingSignatures.checker.signed_at) }}</p>
            </div>
            <button @click="removeSignature('checker')" class="btn btn-danger btn-sm">
              🗑️ Remove Signature
            </button>
          </div>
          
          <div v-else class="signature-input">
            <DigitalSignature 
              role="checker" 
              @signature-saved="handleSignatureSaved"
            />
          </div>
        </div>

        <div class="signature-card">
          <div class="signature-header">
            <h4>Approved By</h4>
            <span class="role-badge approver">Approver</span>
          </div>
          
          <div v-if="existingSignatures.approver" class="existing-signature">
            <div class="signature-image">
              <img :src="existingSignatures.approver.signature_data" alt="Approver Signature" />
            </div>
            <div class="signature-info">
              <p class="signer-name">{{ existingSignatures.approver.user_name }}</p>
              <p class="sign-date">{{ formatDate(existingSignatures.approver.signed_at) }}</p>
            </div>
            <button @click="removeSignature('approver')" class="btn btn-danger btn-sm">
              🗑️ Remove Signature
            </button>
          </div>
          
          <div v-else class="signature-input">
            <DigitalSignature 
              role="approver" 
              @signature-saved="handleSignatureSaved"
            />
          </div>
        </div>
      </div>
      
      <!-- Signature Status Summary -->
      <div class="signature-status">
        <h4>Signature Status</h4>
        <div class="status-grid">
          <div class="status-item">
            <span class="status-icon" :class="{ signed: existingSignatures.inspector }">
              {{ existingSignatures.inspector ? '✅' : '⏳' }}
            </span>
            <span class="status-text">Inspector</span>
          </div>
          <div class="status-item">
            <span class="status-icon" :class="{ signed: existingSignatures.checker }">
              {{ existingSignatures.checker ? '✅' : '⏳' }}
            </span>
            <span class="status-text">Checker</span>
          </div>
          <div class="status-item">
            <span class="status-icon" :class="{ signed: existingSignatures.approver }">
              {{ existingSignatures.approver ? '✅' : '⏳' }}
            </span>
            <span class="status-text">Approver</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, reactive, watch } from 'vue'
import { useInspectionStore } from '../../stores/inspection'
import DigitalSignature from '../common/DigitalSignature.vue'

const props = defineProps({
  modelValue: {
    type: Array,
    default: () => []
  },
  inspectionId: {
    type: [String, Number],
    default: null
  }
})

const emit = defineEmits(['update:modelValue', 'signature-added'])

const inspectionStore = useInspectionStore()
const localData = reactive([...props.modelValue])

const existingSignatures = computed(() => {
  const signatures = {}
  localData.forEach(sig => {
    signatures[sig.role] = sig
  })
  return signatures
})

const handleSignatureSaved = async (signatureData) => {
  try {
    if (props.inspectionId) {
      // Save to backend
      const savedSignature = await inspectionStore.addSignature(props.inspectionId, signatureData)
      
      // Update local data
      const existingIndex = localData.findIndex(s => s.role === signatureData.role)
      if (existingIndex !== -1) {
        localData[existingIndex] = savedSignature
      } else {
        localData.push(savedSignature)
      }
      
      emit('signature-added', savedSignature)
    } else {
      // Just add to local state for new inspections
      const existingIndex = localData.findIndex(s => s.role === signatureData.role)
      if (existingIndex !== -1) {
        localData[existingIndex] = signatureData
      } else {
        localData.push(signatureData)
      }
      
      emit('signature-added', signatureData)
    }
    
    updateData()
  } catch (error) {
    console.error('Failed to save signature:', error)
    alert('Failed to save signature. Please try again.')
  }
}

const removeSignature = (role) => {
  const index = localData.findIndex(sig => sig.role === role)
  if (index !== -1) {
    localData.splice(index, 1)
    updateData()
  }
}

const updateData = () => {
  emit('update:modelValue', [...localData])
}

const formatDate = (dateString) => {
  if (!dateString) return ''
  return new Date(dateString).toLocaleDateString('id-ID', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

// Watch for external changes to modelValue
watch(() => props.modelValue, (newValue) => {
  localData.splice(0, localData.length, ...newValue)
}, { deep: true })
</script>

<style scoped>
/* .signature-section {*/
  /* No border-bottom since this is usually the last section */
/*} */

.section-header {
  background: #f8f9fa;
  padding: 1.5rem;
  border-bottom: 1px solid #dee2e6;
}

.section-header h3 {
  margin: 0 0 0.5rem 0;
  color: #495057;
  font-size: 1.25rem;
}

.section-subtitle {
  margin: 0;
  color: #6c757d;
  font-size: 0.9rem;
}

.signature-content {
  padding: 2rem;
}

.signatures-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
  gap: 2rem;
  margin-bottom: 2rem;
}

.signature-card {
  border: 1px solid #dee2e6;
  border-radius: 0.5rem;
  overflow: hidden;
  background: white;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.signature-header {
  background: #e9ecef;
  padding: 1rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
  border-bottom: 1px solid #dee2e6;
}

.signature-header h4 {
  margin: 0;
  color: #495057;
  font-size: 1rem;
}

.role-badge {
  padding: 0.25rem 0.75rem;
  border-radius: 1rem;
  font-size: 0.75rem;
  font-weight: bold;
  text-transform: uppercase;
}

.role-badge.inspector {
  background-color: #007bff;
  color: white;
}

.role-badge.checker {
  background-color: #ffc107;
  color: #212529;
}

.role-badge.approver {
  background-color: #28a745;
  color: white;
}

.existing-signature {
  padding: 1.5rem;
  text-align: center;
}

.signature-image {
  margin-bottom: 1rem;
  display: flex;
  justify-content: center;
}

.signature-image img {
  max-width: 250px;
  max-height: 120px;
  border: 1px solid #dee2e6;
  border-radius: 0.375rem;
  background-color: white;
  box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.signature-info {
  margin-bottom: 1rem;
}

.signer-name {
  font-weight: bold;
  margin: 0 0 0.25rem 0;
  color: #495057;
  font-size: 1rem;
}

.sign-date {
  font-size: 0.85rem;
  color: #6c757d;
  margin: 0;
}

.signature-input {
  padding: 1rem;
}

.btn {
  padding: 0.5rem 1rem;
  border: none;
  border-radius: 0.375rem;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s;
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  text-decoration: none;
}

.btn-danger {
  background-color: #dc3545;
  color: white;
}

.btn-danger:hover {
  background-color: #c82333;
  transform: translateY(-1px);
}

.btn-sm {
  padding: 0.375rem 0.75rem;
  font-size: 0.875rem;
}

.signature-status {
  background: #f8f9fa;
  border: 1px solid #dee2e6;
  border-radius: 0.5rem;
  padding: 1.5rem;
  text-align: center;
}

.signature-status h4 {
  margin: 0 0 1rem 0;
  color: #495057;
  font-size: 1rem;
}

.status-grid {
  display: flex;
  justify-content: center;
  gap: 2rem;
}

.status-item {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 0.5rem;
}

.status-icon {
  font-size: 1.5rem;
  opacity: 0.5;
  transition: opacity 0.3s;
}

.status-icon.signed {
  opacity: 1;
}

.status-text {
  font-size: 0.85rem;
  color: #6c757d;
  font-weight: 500;
}

@media (max-width: 768px) {
  .signature-content {
    padding: 1rem;
  }
  
  .signatures-grid {
    grid-template-columns: 1fr;
    gap: 1rem;
  }
  
  .signature-header {
    padding: 0.75rem;
  }
  
  .existing-signature {
    padding: 1rem;
  }
  
  .signature-image img {
    max-width: 200px;
    max-height: 100px;
  }
  
  .status-grid {
    flex-direction: column;
    gap: 1rem;
  }
  
  .section-header {
    padding: 1rem;
  }
}
</style>