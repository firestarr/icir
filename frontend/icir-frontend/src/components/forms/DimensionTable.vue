<template>
  <div class="dimension-section">
    <div class="section-header">
      <h3>📏 DIMENSION INSPECTION</h3>
      <div class="sample-size-info">
        <strong>SAMPLE SIZE: _______ (FOLLOW STANDARD SAMPLING CHECK)</strong>
      </div>
    </div>
    
    <div class="table-container">
      <table class="dimension-table">
        <thead>
          <tr>
            <th rowspan="2" class="dimension-col">SPECIFICATION</th>
            <th rowspan="2" class="tolerance-col">TOL.</th>
            <th colspan="10" class="samples-header">INSPECTION DATA (DIMENSION)</th>
            <th rowspan="2" class="average-col">Rata-Rata</th>
            <th rowspan="2" class="remark-col">Remark</th>
          </tr>
          <tr>
            <th v-for="i in 10" :key="i" class="sample-col">{{ i }}</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(dimension, index) in localData" :key="index" class="dimension-row">
            <td class="dimension-cell">
              <div class="dimension-label">{{ getDimensionLabel(dimension.dimension_type) }}</div>
              <input 
                v-model="dimension.specification" 
                type="text" 
                class="table-input"
                placeholder="Enter specification"
                @input="updateData"
              />
            </td>
            
            <td class="tolerance-cell">
              <input 
                v-model="dimension.tolerance" 
                type="text" 
                class="table-input"
                placeholder="±"
                @input="updateData"
              />
            </td>
            
            <td v-for="i in 10" :key="i" class="sample-cell">
              <input 
                v-model.number="dimension[`sample_${i}`]" 
                type="number" 
                step="0.001"
                class="table-input sample-input"
                :placeholder="`${i}`"
                @input="calculateAverage(index)"
              />
            </td>
            
            <td class="average-cell">
              <span class="average-display">{{ getAverageValue(dimension) }}</span>
            </td>
            
            <td class="remark-cell">
              <input 
                v-model="dimension.remark" 
                type="text" 
                class="table-input"
                placeholder="Enter remark"
                @input="updateData"
              />
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { reactive, watch } from 'vue'

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

const getAverageValue = (dimension) => {
  const samples = []
  for (let i = 1; i <= 10; i++) {
    const value = dimension[`sample_${i}`]
    if (value !== null && value !== undefined && value !== '') {
      const numValue = parseFloat(value)
      if (!isNaN(numValue)) {
        samples.push(numValue)
      }
    }
  }
  
  if (samples.length === 0) return ''
  
  const average = samples.reduce((sum, val) => sum + val, 0) / samples.length
  return average.toFixed(3)
}

const calculateAverage = (index) => {
  const dimension = localData[index]
  const average = getAverageValue(dimension)
  
  // Update the rata_rata field
  dimension.rata_rata = average
  
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
.dimension-section {
  border-bottom: 1px solid #dee2e6;
}

.section-header {
  background: #f8f9fa;
  padding: 1.5rem;
  border-bottom: 1px solid #dee2e6;
}

.section-header h3 {
  margin: 0 0 1rem 0;
  color: #495057;
  font-size: 1.25rem;
}

.sample-size-info {
  color: #6c757d;
  font-size: 0.9rem;
}

.table-container {
  overflow-x: auto;
  padding: 1rem;
}

.dimension-table {
  width: 100%;
  border-collapse: collapse;
  font-size: 0.85rem;
  min-width: 1200px;
}

.dimension-table th,
.dimension-table td {
  border: 1px solid #dee2e6;
  text-align: center;
  vertical-align: middle;
}

.dimension-table th {
  background-color: #e9ecef;
  font-weight: 600;
  color: #495057;
  padding: 0.75rem 0.5rem;
}

.samples-header {
  background-color: #007bff !important;
  color: white !important;
}

.dimension-col {
  width: 200px;
  min-width: 200px;
}

.tolerance-col {
  width: 80px;
  min-width: 80px;
}

.sample-col {
  width: 60px;
  min-width: 60px;
}

.average-col {
  width: 80px;
  min-width: 80px;
  background-color: #e7f3ff !important;
}

.remark-col {
  width: 150px;
  min-width: 150px;
}

.dimension-row:nth-child(even) {
  background-color: #f8f9fa;
}

.dimension-cell {
  text-align: left;
  padding: 0.5rem;
}

.dimension-label {
  font-weight: 600;
  color: #495057;
  margin-bottom: 0.5rem;
  font-size: 0.8rem;
}

.tolerance-cell,
.sample-cell,
.remark-cell {
  padding: 0.25rem;
}

.average-cell {
  padding: 0.5rem;
  background-color: #e7f3ff;
}

.table-input {
  width: 100%;
  border: none;
  padding: 0.375rem;
  text-align: center;
  font-size: 0.8rem;
  background: transparent;
  border-radius: 0.25rem;
}

.table-input:focus {
  outline: 2px solid #007bff;
  background-color: white;
}

.sample-input {
  font-family: 'Courier New', monospace;
  font-weight: 500;
}

.average-display {
  font-weight: bold;
  color: #007bff;
  font-family: 'Courier New', monospace;
}

.dimension-cell .table-input {
  text-align: left;
}

.remark-cell .table-input {
  text-align: left;
}

/* Responsive adjustments */
@media (max-width: 768px) {
  .table-container {
    padding: 0.5rem;
  }
  
  .dimension-table {
    font-size: 0.75rem;
  }
  
  .section-header {
    padding: 1rem;
  }
  
  .section-header h3 {
    font-size: 1.1rem;
  }
  
  .table-input {
    padding: 0.25rem;
    font-size: 0.75rem;
  }
}

/* Print styles */
@media print {
  .dimension-table {
    font-size: 10px;
  }
  
  .table-input {
    border: 1px solid #000 !important;
    padding: 2px;
  }
}
</style>