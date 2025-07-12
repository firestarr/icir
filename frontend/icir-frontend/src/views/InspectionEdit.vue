<template>
  <div class="inspection-edit">
    <div class="page-header">
      <h1>Edit Inspection Record</h1>
      <div class="header-actions">
        <router-link :to="`/inspections/${route.params.id}/view`" class="btn btn-info">
          👁️ View Mode
        </router-link>
        <router-link to="/inspections" class="btn btn-secondary">
          ← Back to List
        </router-link>
      </div>
    </div>

    <div class="page-content">
      <InspectionForm 
        mode="edit" 
        :inspection-id="route.params.id"
        @updated="handleInspectionUpdated"
      />
    </div>
  </div>
</template>

<script setup>
import { useRoute, useRouter } from 'vue-router'
import InspectionForm from '../components/forms/InspectionForm.vue'

const route = useRoute()
const router = useRouter()

const handleInspectionUpdated = (inspection) => {
  // Redirect to view page after successful update
  router.push(`/inspections/${inspection.id}/view`)
}
</script>

<style scoped>
.inspection-edit {
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

.page-header h1 {
  margin: 0;
  color: #495057;
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
}

.btn-info {
  background-color: #17a2b8;
  color: white;
}

.btn-info:hover {
  background-color: #138496;
}

.btn-secondary {
  background-color: #6c757d;
  color: white;
}

.btn-secondary:hover {
  background-color: #5a6268;
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
}
</style>