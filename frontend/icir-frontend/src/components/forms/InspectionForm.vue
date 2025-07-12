<template>
  <div class="inspection-form">
    <form @submit.prevent="handleSubmit">
      <!-- Header Section -->
      <HeaderSection v-model="formData" :errors="errors" />
      
      <!-- Dimension Table -->
      <DimensionTable v-model="formData.dimensions" :errors="errors" />
      
      <!-- Visual Check Section -->
      <VisualCheckSection v-model="formData.visual_checks" :errors="errors" />
      
      <!-- Signature Section -->
      <SignatureSection 
        v-model="formData.signatures" 
        :inspection-id="inspectionId"
        @signature-added="handleSignatureAdded"
      />

      <!-- Action Buttons -->
      <div class="form-actions">
        <button type="button" @click="saveDraft" class="btn btn-secondary" :disabled="loading">
          💾 Save Draft
        </button>
        <button type="submit" class="btn btn-primary" :disabled="loading">
          <span v-if="loading">⏳ Saving...</span>
          <span v-else>{{ submitLabel }}</span>
        </button>
        <button type="button" @click="printForm" class="btn btn-success" v-if="inspectionId">
          🖨️ Print PDF
        </button>
      </div>
    </form>

    <!-- Loading Overlay -->
    <div v-if="loading" class="loading-overlay">
      <div class="spinner"></div>
    </div>
  </div>
</template>

/* eslint-disable no-undef */
<script setup>
import { ref, reactive, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useInspectionStore } from '../../stores/inspection'
import HeaderSection from './HeaderSection.vue'
import DimensionTable from './DimensionTable.vue'
import VisualCheckSection from './VisualCheckSection.vue'
import SignatureSection from './SignatureSection.vue'

const props = defineProps({
  inspectionId: {
    type: [String, Number],
    default: null
  },
  mode: {
    type: String,
    default: 'create' // create, edit, view
  }
})

const emit = defineEmits(['created', 'updated'])

const router = useRouter()
const inspectionStore = useInspectionStore()

const errors = ref({})
const loading = ref(false)

const formData = reactive({
  material: '',
  po_no: '',
  sample_size: '',
  supplier: '',
  issue_date: '',
  instrument_used: '',
  description: '',
  location: '',
  nomer_instrument: '',
  ic_no: '',
  lot_size_qty: '',
  qty_good: '',
  unit: '',
  data_outgoing: '',
  qty_no_good: '',
  coa_data: '',
  icp_data: '',
  aql: '',
  acc_reject: '',
  supplier_lot_no: '',
  dimensions: [],
  visual_checks: [],
  signatures: []
})

const submitLabel = computed(() => {
  return props.mode === 'create' ? '✅ Create Inspection' : '💾 Update Inspection'
})

// Initialize default dimension data
const initializeDimensions = () => {
  const dimensionTypes = [
    { type: 'thickness', label: 'THICKNESS' },
    { type: 'width', label: 'WIDTH' },
    { type: 'length', label: 'LENGTH' },
    { type: 'diameter', label: 'DIAMETER' },
    { type: 'other', label: 'OTHER' }
  ]

  formData.dimensions = dimensionTypes.map(dim => ({
    dimension_type: dim.type,
    specification: '',
    tolerance: '',
    sample_1: null,
    sample_2: null,
    sample_3: null,
    sample_4: null,
    sample_5: null,
    sample_6: null,
    sample_7: null,
    sample_8: null,
    sample_9: null,
    sample_10: null,
    remark: ''
  }))
}

// Initialize default visual checks
const initializeVisualChecks = () => {
  const checkTypes = [
    'DIRTY (Oil, Dust)',
    'WRONG COLOR',
    'WRINGKLE/BUBBLE',
    'SCRATCHS/FLASH',
    'TEAR/TORN',
    'BROKEN / DAMAGE',
    'CHECK ICP',
    'CHECK COA',
    'CHECK SAFETY',
    'OTHER'
  ]

  formData.visual_checks = checkTypes.map(type => ({
    check_type: type,
    accepted: false,
    rejected: false,
    remark: ''
  }))
}

const handleSubmit = async () => {
  try {
    loading.value = true
    errors.value = {}
    
    if (props.mode === 'create') {
      const inspection = await inspectionStore.createInspection(formData)
      emit('created', inspection)
    } else {
      const inspection = await inspectionStore.updateInspection(props.inspectionId, formData)
      emit('updated', inspection)
    }
  } catch (error) {
    if (error.response && error.response.data.errors) {
      errors.value = error.response.data.errors
    } else {
      alert('Failed to save inspection. Please try again.')
    }
  } finally {
    loading.value = false
  }
}

const saveDraft = async () => {
  try {
    loading.value = true
    errors.value = {}
    
    const draftData = { ...formData, status: 'draft' }
    
    if (props.mode === 'create') {
      const inspection = await inspectionStore.createInspection(draftData)
      emit('created', inspection)
    } else {
      const inspection = await inspectionStore.updateInspection(props.inspectionId, draftData)
      emit('updated', inspection)
    }
    
    alert('Draft saved successfully!')
  } catch (error) {
    console.error('Failed to save draft:', error)
    alert('Failed to save draft. Please try again.')
  } finally {
    loading.value = false
  }
}

const printForm = () => {
  if (props.inspectionId) {
    window.open(`/api/v1/inspections/${props.inspectionId}/pdf`, '_blank')
  }
}

const handleSignatureAdded = (signature) => {
  const existingIndex = formData.signatures.findIndex(s => s.role === signature.role)
  if (existingIndex !== -1) {
    formData.signatures[existingIndex] = signature
  } else {
    formData.signatures.push(signature)
  }
}

// Load inspection data if editing
onMounted(async () => {
  if (props.mode === 'create') {
    initializeDimensions()
    initializeVisualChecks()
  } else if (props.inspectionId) {
    try {
      loading.value = true
      const inspection = await inspectionStore.fetchInspection(props.inspectionId)
      
      // Populate form data
      Object.keys(formData).forEach(key => {
        if (inspection[key] !== undefined) {
          formData[key] = inspection[key]
        }
      })
      
      // Ensure dimensions and visual_checks are arrays
      if (!formData.dimensions || formData.dimensions.length === 0) {
        initializeDimensions()
      }
      if (!formData.visual_checks || formData.visual_checks.length === 0) {
        initializeVisualChecks()
      }
      
    } catch (error) {
      console.error('Failed to load inspection:', error)
      alert('Failed to load inspection data.')
      router.push('/inspections')
    } finally {
      loading.value = false
    }
  }
})
</script>

<style scoped>
.inspection-form {
  position: relative;
  max-width: 1200px;
  margin: 0 auto;
  background: white;
  border-radius: 0.5rem;
  border: 1px solid #dee2e6;
  overflow: hidden;
}

.form-actions {
  padding: 2rem;
  background: #f8f9fa;
  border-top: 1px solid #dee2e6;
  display: flex;
  gap: 1rem;
  justify-content: flex-end;
  align-items: center;
}

.btn {
  padding: 0.75rem 1.5rem;
  border: none;
  border-radius: 0.375rem;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s;
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
}

.btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.btn-primary {
  background-color: #007bff;
  color: white;
}

.btn-primary:hover:not(:disabled) {
  background-color: #0056b3;
  transform: translateY(-1px);
}

.btn-secondary {
  background-color: #6c757d;
  color: white;
}

.btn-secondary:hover:not(:disabled) {
  background-color: #545b62;
}

.btn-success {
  background-color: #28a745;
  color: white;
}

.btn-success:hover:not(:disabled) {
  background-color: #218838;
}

.loading-overlay {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(255, 255, 255, 0.8);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
}

.spinner {
  width: 50px;
  height: 50px;
  border: 5px solid #f3f3f3;
  border-top: 5px solid #007bff;
  border-radius: 50%;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

@media (max-width: 768px) {
  .form-actions {
    flex-direction: column;
    align-items: stretch;
  }
  
  .btn {
    justify-content: center;
  }
}
</style>