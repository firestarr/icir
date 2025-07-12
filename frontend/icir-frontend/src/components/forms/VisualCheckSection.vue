<template>
  <div class="visual-check-section">
    <div class="section-header">
      <h3>👁️ VISUAL CHECK</h3>
      <p class="section-subtitle">Check each item and mark as accepted or rejected</p>
    </div>
    
    <div class="visual-check-content">
      <div class="check-columns">
        <div class="check-column">
          <div class="column-header">
            <span class="check-label">B. VISUAL CHECK</span>
            <span class="status-header accepted">ACCEPTED</span>
            <span class="status-header rejected">REJECTED</span>
            <span class="remark-header">REMARK</span>
          </div>
          
          <div v-for="(check, index) in leftColumnChecks" :key="check.check_type" class="check-row">
            <div class="check-type-cell">
              <span class="check-number">B.{{ index + 1 }}</span>
              <span class="check-name">{{ check.check_type }}</span>
            </div>
            
            <div class="status-cell">
              <label class="checkbox-container">
                <input 
                  v-model="check.accepted" 
                  type="checkbox" 
                  @change="updateCheck(getCheckIndex(check), 'accepted', check.accepted)"
                />
                <span class="checkmark accepted-check"></span>
              </label>
            </div>
            
            <div class="status-cell">
              <label class="checkbox-container">
                <input 
                  v-model="check.rejected" 
                  type="checkbox"
                  @change="updateCheck(getCheckIndex(check), 'rejected', check.rejected)"
                />
                <span class="checkmark rejected-check"></span>
              </label>
            </div>
            
            <div class="remark-cell">
              <input 
                v-model="check.remark"
                type="text" 
                class="remark-input"
                placeholder="Enter remark"
                @input="updateCheck(getCheckIndex(check), 'remark', check.remark)"
              />
            </div>
          </div>
        </div>

        <div class="check-column">
          <div class="column-header">
            <span class="check-label">B. VISUAL CHECK</span>
            <span class="status-header accepted">ACCEPTED</span>
            <span class="status-header rejected">REJECTED</span>
            <span class="remark-header">REMARK</span>
          </div>
          
          <div v-for="(check, index) in rightColumnChecks" :key="check.check_type" class="check-row">
            <div class="check-type-cell">
              <span class="check-number">B.{{ index + 6 }}</span>
              <span class="check-name">{{ check.check_type }}</span>
            </div>
            
            <div class="status-cell">
              <label class="checkbox-container">
                <input 
                  v-model="check.accepted" 
                  type="checkbox"
                  @change="updateCheck(getCheckIndex(check), 'accepted', check.accepted)"
                />
                <span class="checkmark accepted-check"></span>
              </label>
            </div>
            
            <div class="status-cell">
              <label class="checkbox-container">
                <input 
                  v-model="check.rejected" 
                  type="checkbox"
                  @change="updateCheck(getCheckIndex(check), 'rejected', check.rejected)"
                />
                <span class="checkmark rejected-check"></span>
              </label>
            </div>
            
            <div class="remark-cell">
              <input 
                v-model="check.remark"
                type="text" 
                class="remark-input"
                placeholder="Enter remark"
                @input="updateCheck(getCheckIndex(check), 'remark', check.remark)"
              />
            </div>
          </div>
        </div>
      </div>
      
      <!-- Summary Section -->
      <div class="check-summary">
        <div class="summary-item">
          <span class="summary-label">Total Accepted:</span>
          <span class="summary-value accepted">{{ acceptedCount }}</span>
        </div>
        <div class="summary-item">
          <span class="summary-label">Total Rejected:</span>
          <span class="summary-value rejected">{{ rejectedCount }}</span>
        </div>
        <div class="summary-item">
          <span class="summary-label">Pending:</span>
          <span class="summary-value pending">{{ pendingCount }}</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>

import { reactive, computed, watch } from 'vue'

const props = defineProps({
  modelValue: {
    type: Array,
    required: true
  },
  errors: {
    type: Object,
    default: () => ({})
  }
})

const emit = defineEmits(['update:modelValue'])

const localData = reactive([...props.modelValue])

const leftColumnChecks = computed(() => {
  return localData.slice(0, 5)
})

const rightColumnChecks = computed(() => {
  return localData.slice(5)
})

const acceptedCount = computed(() => {
  return localData.filter(check => check.accepted).length
})

const rejectedCount = computed(() => {
  return localData.filter(check => check.rejected).length
})

const pendingCount = computed(() => {
  return localData.filter(check => !check.accepted && !check.rejected).length
})

const getCheckIndex = (check) => {
  return localData.findIndex(c => c.check_type === check.check_type)
}

const updateCheck = (index, field, value) => {
  if (index === -1) return
  
  const updatedCheck = { ...localData[index], [field]: value }
  
  // If accepted is checked, uncheck rejected and vice versa
  if (field === 'accepted' && value) {
    updatedCheck.rejected = false
  } else if (field === 'rejected' && value) {
    updatedCheck.accepted = false
  }
  
  localData[index] = updatedCheck
  updateData()
}

const updateData = () => {
  emit('update:modelValue', [...localData])
}

// Watch for external changes to modelValue
watch(() => props.modelValue, (newValue) => {
  localData.splice(0, localData.length, ...newValue)
}, { deep: true })
</script>

<style scoped>
.visual-check-section {
  border-bottom: 1px solid #dee2e6;
}

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

.visual-check-content {
  padding: 1.5rem;
}

.check-columns {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 2rem;
  margin-bottom: 2rem;
}

.check-column {
  border: 1px solid #dee2e6;
  border-radius: 0.375rem;
  overflow: hidden;
}

.column-header {
  display: grid;
  grid-template-columns: 2fr 1fr 1fr 2fr;
  background: #e9ecef;
  border-bottom: 1px solid #dee2e6;
  padding: 0.75rem 0.5rem;
  font-weight: 600;
  font-size: 0.85rem;
  text-align: center;
}

.check-label {
  text-align: left;
  padding-left: 1rem;
}

.status-header.accepted {
  color: #28a745;
}

.status-header.rejected {
  color: #dc3545;
}

.remark-header {
  color: #495057;
}

.check-row {
  display: grid;
  grid-template-columns: 2fr 1fr 1fr 2fr;
  align-items: center;
  padding: 0.75rem 0.5rem;
  border-bottom: 1px solid #f1f3f4;
}

.check-row:last-child {
  border-bottom: none;
}

.check-row:nth-child(even) {
  background-color: #f8f9fa;
}

.check-type-cell {
  display: flex;
  flex-direction: column;
  text-align: left;
  padding-left: 1rem;
}

.check-number {
  font-weight: 600;
  color: #007bff;
  font-size: 0.8rem;
}

.check-name {
  font-size: 0.85rem;
  color: #495057;
  margin-top: 0.25rem;
}

.status-cell {
  display: flex;
  justify-content: center;
  align-items: center;
}

.checkbox-container {
  position: relative;
  cursor: pointer;
  font-size: 16px;
  user-select: none;
}

.checkbox-container input {
  position: absolute;
  opacity: 0;
  cursor: pointer;
}

.checkmark {
  height: 20px;
  width: 20px;
  border: 2px solid #ced4da;
  border-radius: 0.25rem;
  display: inline-block;
  position: relative;
  transition: all 0.2s;
}

.checkbox-container:hover .checkmark {
  border-color: #007bff;
}

.checkbox-container input:checked ~ .accepted-check {
  background-color: #28a745;
  border-color: #28a745;
}

.checkbox-container input:checked ~ .rejected-check {
  background-color: #dc3545;
  border-color: #dc3545;
}

.checkmark:after {
  content: "";
  position: absolute;
  display: none;
}

.checkbox-container input:checked ~ .checkmark:after {
  display: block;
}

.checkbox-container .checkmark:after {
  left: 6px;
  top: 2px;
  width: 4px;
  height: 8px;
  border: solid white;
  border-width: 0 2px 2px 0;
  transform: rotate(45deg);
}

.remark-cell {
  padding: 0 0.5rem;
}

.remark-input {
  width: 100%;
  padding: 0.375rem;
  border: 1px solid #ced4da;
  border-radius: 0.25rem;
  font-size: 0.8rem;
}

.remark-input:focus {
  outline: none;
  border-color: #007bff;
  box-shadow: 0 0 0 0.1rem rgba(0, 123, 255, 0.25);
}

.check-summary {
  display: flex;
  justify-content: center;
  gap: 2rem;
  padding: 1rem;
  background: #f8f9fa;
  border-radius: 0.375rem;
  border: 1px solid #dee2e6;
}

.summary-item {
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
}

.summary-label {
  font-size: 0.8rem;
  color: #6c757d;
  margin-bottom: 0.25rem;
}

.summary-value {
  font-size: 1.5rem;
  font-weight: bold;
}

.summary-value.accepted {
  color: #28a745;
}

.summary-value.rejected {
  color: #dc3545;
}

.summary-value.pending {
  color: #ffc107;
}

@media (max-width: 992px) {
  .check-columns {
    grid-template-columns: 1fr;
    gap: 1rem;
  }
  
  .visual-check-content {
    padding: 1rem;
  }
  
  .check-summary {
    flex-direction: column;
    gap: 1rem;
  }
}

@media (max-width: 768px) {
  .column-header,
  .check-row {
    grid-template-columns: 2fr 0.8fr 0.8fr 1.5fr;
    font-size: 0.75rem;
  }
  
  .check-name {
    font-size: 0.75rem;
  }
  
  .section-header {
    padding: 1rem;
  }
  
  .checkmark {
    height: 16px;
    width: 16px;
  }
  
  .remark-input {
    font-size: 0.75rem;
    padding: 0.25rem;
  }
}
</style>