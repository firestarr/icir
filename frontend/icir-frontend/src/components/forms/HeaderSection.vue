<template>
  <div class="header-section">
    <div class="section-header">
      <h2>📋 INCOMING INSPECTION RECORD (ICIR)</h2>
    </div>
    
    <div class="form-content">
      <div class="form-grid">
        <div class="form-row">
          <div class="form-group">
            <label for="material">Material <span class="required">*</span></label>
            <input 
              id="material"
              v-model="localData.material" 
              type="text" 
              class="form-input"
              :class="{ 'error': errors.material }"
              placeholder="Enter material name"
              @input="updateData"
            />
            <span v-if="errors.material" class="error-message">{{ errors.material[0] }}</span>
          </div>
          
          <div class="form-group">
            <label for="po_no">PO No. <span class="required">*</span></label>
            <input 
              id="po_no"
              v-model="localData.po_no" 
              type="text" 
              class="form-input"
              :class="{ 'error': errors.po_no }"
              placeholder="Enter PO number"
              @input="updateData"
            />
            <span v-if="errors.po_no" class="error-message">{{ errors.po_no[0] }}</span>
          </div>
          
          <div class="form-group">
            <label for="sample_size">Sample Size</label>
            <input 
              id="sample_size"
              v-model="localData.sample_size" 
              type="text" 
              class="form-input"
              placeholder="Enter sample size"
              @input="updateData"
            />
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="supplier">Supplier <span class="required">*</span></label>
            <input 
              id="supplier"
              v-model="localData.supplier" 
              type="text" 
              class="form-input"
              :class="{ 'error': errors.supplier }"
              placeholder="Enter supplier name"
              @input="updateData"
            />
            <span v-if="errors.supplier" class="error-message">{{ errors.supplier[0] }}</span>
          </div>
          
          <div class="form-group">
            <label for="issue_date">Issue Date</label>
            <input 
              id="issue_date"
              v-model="localData.issue_date" 
              type="date" 
              class="form-input"
              @input="updateData"
            />
          </div>
          
          <div class="form-group">
            <label for="instrument_used">Instrument Used</label>
            <input 
              id="instrument_used"
              v-model="localData.instrument_used" 
              type="text" 
              class="form-input"
              placeholder="Enter instrument used"
              @input="updateData"
            />
          </div>
        </div>

        <div class="form-row">
          <div class="form-group span-2">
            <label for="description">Description</label>
            <textarea 
              id="description"
              v-model="localData.description" 
              class="form-input"
              rows="3"
              placeholder="Enter description"
              @input="updateData"
            ></textarea>
          </div>
          
          <div class="form-group">
            <label for="location">Location</label>
            <input 
              id="location"
              v-model="localData.location" 
              type="text" 
              class="form-input"
              placeholder="Enter location"
              @input="updateData"
            />
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="nomer_instrument">Nomer Instrument</label>
            <input 
              id="nomer_instrument"
              v-model="localData.nomer_instrument" 
              type="text" 
              class="form-input"
              placeholder="Enter instrument number"
              @input="updateData"
            />
          </div>
          
          <div class="form-group">
            <label for="ic_no">IC No.</label>
            <input 
              id="ic_no"
              v-model="localData.ic_no" 
              type="text" 
              class="form-input"
              placeholder="Enter IC number"
              @input="updateData"
            />
          </div>
          
          <div class="form-group">
            <label for="lot_size_qty">Lot Size Qty</label>
            <input 
              id="lot_size_qty"
              v-model="localData.lot_size_qty" 
              type="text" 
              class="form-input"
              placeholder="Enter lot size quantity"
              @input="updateData"
            />
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="qty_good">Qty Good</label>
            <input 
              id="qty_good"
              v-model="localData.qty_good" 
              type="text" 
              class="form-input"
              placeholder="Enter good quantity"
              @input="updateData"
            />
          </div>
          
          <div class="form-group">
            <label for="unit">Unit</label>
            <input 
              id="unit"
              v-model="localData.unit" 
              type="text" 
              class="form-input"
              placeholder="Enter unit"
              @input="updateData"
            />
          </div>
          
          <div class="form-group">
            <label for="qty_no_good">Qty No Good</label>
            <input 
              id="qty_no_good"
              v-model="localData.qty_no_good" 
              type="text" 
              class="form-input"
              placeholder="Enter no good quantity"
              @input="updateData"
            />
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="data_outgoing">Data OutGoing</label>
            <select 
              id="data_outgoing"
              v-model="localData.data_outgoing" 
              class="form-input"
              @change="updateData"
            >
              <option value="">Select...</option>
              <option value="Ada">Ada</option>
              <option value="Tidak Ada">Tidak Ada</option>
            </select>
          </div>
          
          <div class="form-group">
            <label for="coa_data">COA Data</label>
            <select 
              id="coa_data"
              v-model="localData.coa_data" 
              class="form-input"
              @change="updateData"
            >
              <option value="">Select...</option>
              <option value="Ada">Ada</option>
              <option value="Tidak Ada">Tidak Ada</option>
            </select>
          </div>
          
          <div class="form-group">
            <label for="icp_data">ICP Data</label>
            <select 
              id="icp_data"
              v-model="localData.icp_data" 
              class="form-input"
              @change="updateData"
            >
              <option value="">Select...</option>
              <option value="Valid">Valid</option>
              <option value="Expired">Expired</option>
            </select>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="aql">AQL</label>
            <input 
              id="aql"
              v-model="localData.aql" 
              type="text" 
              class="form-input"
              placeholder="Enter AQL"
              @input="updateData"
            />
          </div>
          
          <div class="form-group">
            <label for="acc_reject">ACC / REJECT</label>
            <select 
              id="acc_reject"
              v-model="localData.acc_reject" 
              class="form-input"
              @change="updateData"
            >
              <option value="">Select...</option>
              <option value="ACC">ACC</option>
              <option value="REJECT">REJECT</option>
            </select>
          </div>
          
          <div class="form-group">
            <label for="supplier_lot_no">Supplier Lot No.</label>
            <input 
              id="supplier_lot_no"
              v-model="localData.supplier_lot_no" 
              type="text" 
              class="form-input"
              placeholder="Enter supplier lot number"
              @input="updateData"
            />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { reactive, watch } from 'vue'

const props = defineProps({
  modelValue: {
    type: Object,
    required: true
  },
  errors: {
    type: Object,
    default: () => ({})
  }
})

const emit = defineEmits(['update:modelValue'])

const localData = reactive({ ...props.modelValue })

const updateData = () => {
  emit('update:modelValue', { ...localData })
}

// Watch for external changes to modelValue
watch(() => props.modelValue, (newValue) => {
  Object.assign(localData, newValue)
}, { deep: true })
</script>

<style scoped>
.header-section {
  border-bottom: 1px solid #dee2e6;
}

.section-header {
  background: linear-gradient(135deg, #007bff, #0056b3);
  color: white;
  padding: 1.5rem;
  text-align: center;
}

.section-header h2 {
  margin: 0;
  font-size: 1.5rem;
  font-weight: 600;
}

.form-content {
  padding: 2rem;
}

.form-grid {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.form-row {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 1.5rem;
  align-items: start;
}

.form-group {
  display: flex;
  flex-direction: column;
}

.form-group.span-2 {
  grid-column: span 2;
}

.form-group label {
  font-weight: 600;
  margin-bottom: 0.5rem;
  color: #495057;
  font-size: 0.9rem;
}

.required {
  color: #dc3545;
}

.form-input {
  width: 100%;
  padding: 0.75rem;
  border: 1px solid #ced4da;
  border-radius: 0.375rem;
  font-size: 0.9rem;
  transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

.form-input:focus {
  outline: 0;
  border-color: #80bdff;
  box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.form-input.error {
  border-color: #dc3545;
}

.form-input.error:focus {
  border-color: #dc3545;
  box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
}

.error-message {
  color: #dc3545;
  font-size: 0.8rem;
  margin-top: 0.25rem;
}

textarea.form-input {
  resize: vertical;
  min-height: 80px;
}

select.form-input {
  cursor: pointer;
}

@media (max-width: 992px) {
  .form-row {
    grid-template-columns: repeat(2, 1fr);
  }
  
  .form-group.span-2 {
    grid-column: span 2;
  }
}

@media (max-width: 768px) {
  .form-content {
    padding: 1rem;
  }
  
  .form-row {
    grid-template-columns: 1fr;
    gap: 1rem;
  }
  
  .form-group.span-2 {
    grid-column: span 1;
  }
  
  .section-header {
    padding: 1rem;
  }
  
  .section-header h2 {
    font-size: 1.25rem;
  }
}
</style>