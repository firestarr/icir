<template>
  <div class="inspection-create">
    <div class="page-header">
      <h1>Create New Inspection Record</h1>
      <router-link to="/inspections" class="btn btn-secondary">
        ← Back to List
      </router-link>
    </div>

    <div class="page-content">
      <InspectionForm mode="create" @created="handleInspectionCreated" />
    </div>
  </div>
</template>

<script setup>
import { useRouter } from 'vue-router'
import InspectionForm from '../components/forms/InspectionForm.vue'

const router = useRouter()

const handleInspectionCreated = (inspection) => {
  // Redirect to view page after successful creation
  router.push(`/inspections/${inspection.id}/view`)
}
</script>

<style scoped>
.inspection-create {
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
  
  .page-content {
    padding: 1rem;
  }
}
</style>