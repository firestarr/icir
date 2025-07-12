<template>
  <div class="signature-component">
    <div class="signature-input-section">
      <div class="name-input-group">
        <label for="userName">Your Name</label>
        <input 
          id="userName"
          v-model="userName" 
          type="text" 
          placeholder="Enter your full name"
          class="name-input"
          :class="{ error: showNameError }"
        />
        <span v-if="showNameError" class="error-message">Name is required</span>
      </div>
    </div>
    
    <div class="signature-pad-section">
      <label class="signature-label">Digital Signature</label>
      <div class="signature-pad-container">
        <canvas 
          ref="signatureCanvas"
          @mousedown="startDrawing"
          @mousemove="draw"
          @mouseup="stopDrawing"
          @mouseleave="stopDrawing"
          @touchstart.prevent="startDrawing"
          @touchmove.prevent="draw"
          @touchend.prevent="stopDrawing"
          class="signature-canvas"
          :class="{ error: showSignatureError }"
          width="400"
          height="200"
        ></canvas>
        
        <div class="signature-placeholder" v-if="!hasDrawn">
          <p>✍️ Sign here using your mouse or touch</p>
        </div>
      </div>
      
      <span v-if="showSignatureError" class="error-message">Signature is required</span>
    </div>
    
    <div class="signature-controls">
      <button @click="clearSignature" class="btn btn-secondary" type="button">
        🗑️ Clear
      </button>
      <button @click="saveSignature" class="btn btn-primary" :disabled="!canSave" type="button">
        💾 Save Signature
      </button>
    </div>
    
    <div class="signature-preview" v-if="previewSignature">
      <h5>Preview:</h5>
      <img :src="previewSignature" alt="Signature Preview" class="preview-image" />
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'

const props = defineProps({
  role: {
    type: String,
    required: true
  }
})

const emit = defineEmits(['signature-saved'])

const signatureCanvas = ref(null)
const userName = ref('')
const isDrawing = ref(false)
const hasDrawn = ref(false)
const showNameError = ref(false)
const showSignatureError = ref(false)
const previewSignature = ref('')

let ctx = null
let lastX = 0
let lastY = 0

const canSave = computed(() => {
  return userName.value.trim() !== '' && hasDrawn.value
})

const getCoordinates = (event) => {
  const rect = signatureCanvas.value.getBoundingClientRect()
  const scaleX = signatureCanvas.value.width / rect.width
  const scaleY = signatureCanvas.value.height / rect.height
  
  let clientX, clientY
  
  if (event.touches && event.touches.length > 0) {
    clientX = event.touches[0].clientX
    clientY = event.touches[0].clientY
  } else {
    clientX = event.clientX
    clientY = event.clientY
  }
  
  return {
    x: (clientX - rect.left) * scaleX,
    y: (clientY - rect.top) * scaleY
  }
}

const startDrawing = (event) => {
  isDrawing.value = true
  hasDrawn.value = true
  showSignatureError.value = false
  
  const coords = getCoordinates(event)
  lastX = coords.x
  lastY = coords.y
  
  ctx.beginPath()
  ctx.moveTo(coords.x, coords.y)
}

const draw = (event) => {
  if (!isDrawing.value) return
  
  const coords = getCoordinates(event)
  
  ctx.globalCompositeOperation = 'source-over'
  ctx.beginPath()
  ctx.moveTo(lastX, lastY)
  ctx.lineTo(coords.x, coords.y)
  ctx.stroke()
  
  lastX = coords.x
  lastY = coords.y
}

const stopDrawing = () => {
  if (!isDrawing.value) return
  isDrawing.value = false
  ctx.beginPath()
}

const clearSignature = () => {
  ctx.clearRect(0, 0, signatureCanvas.value.width, signatureCanvas.value.height)
  hasDrawn.value = false
  previewSignature.value = ''
  showSignatureError.value = false
}

const saveSignature = () => {
  // Reset error states
  showNameError.value = false
  showSignatureError.value = false
  
  // Validate name
  if (!userName.value.trim()) {
    showNameError.value = true
    return
  }
  
  // Validate signature
  if (!hasDrawn.value) {
    showSignatureError.value = true
    return
  }
  
  // Generate signature data
  const imageData = signatureCanvas.value.toDataURL('image/png')
  previewSignature.value = imageData
  
  // Emit signature data
  emit('signature-saved', {
    role: props.role,
    signature_data: imageData,
    user_name: userName.value.trim(),
    signed_at: new Date().toISOString()
  })
  
  // Reset form
  clearSignature()
  userName.value = ''
  previewSignature.value = ''
}

onMounted(() => {
  ctx = signatureCanvas.value.getContext('2d')
  
  // Set up canvas drawing properties
  ctx.strokeStyle = '#000000'
  ctx.lineWidth = 2
  ctx.lineCap = 'round'
  ctx.lineJoin = 'round'
  
  // Set white background
  ctx.fillStyle = '#ffffff'
  ctx.fillRect(0, 0, signatureCanvas.value.width, signatureCanvas.value.height)
  
  // Reset composite operation
  ctx.globalCompositeOperation = 'source-over'
})
</script>

<style scoped>
.signature-component {
  background: #f8f9fa;
  border: 1px solid #dee2e6;
  border-radius: 0.5rem;
  padding: 1.5rem;
  margin: 0;
}

.signature-input-section {
  margin-bottom: 1.5rem;
}

.name-input-group {
  display: flex;
  flex-direction: column;
}

.name-input-group label {
  font-weight: 600;
  margin-bottom: 0.5rem;
  color: #495057;
  font-size: 0.9rem;
}

.name-input {
  padding: 0.75rem;
  border: 1px solid #ced4da;
  border-radius: 0.375rem;
  font-size: 0.9rem;
  transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

.name-input:focus {
  outline: 0;
  border-color: #80bdff;
  box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.name-input.error {
  border-color: #dc3545;
}

.signature-pad-section {
  margin-bottom: 1.5rem;
}

.signature-label {
  display: block;
  font-weight: 600;
  margin-bottom: 0.5rem;
  color: #495057;
  font-size: 0.9rem;
}

.signature-pad-container {
  position: relative;
  display: inline-block;
  border: 2px solid #ced4da;
  border-radius: 0.375rem;
  background: white;
  overflow: hidden;
}

.signature-canvas {
  display: block;
  cursor: crosshair;
  background: white;
}

.signature-canvas.error {
  border-color: #dc3545;
}

.signature-placeholder {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  color: #6c757d;
  font-style: italic;
  pointer-events: none;
  text-align: center;
}

.signature-controls {
  display: flex;
  gap: 1rem;
  justify-content: center;
  margin-bottom: 1rem;
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

.btn-secondary:hover {
  background-color: #545b62;
}

.error-message {
  color: #dc3545;
  font-size: 0.8rem;
  margin-top: 0.25rem;
  display: block;
}

.signature-preview {
  margin-top: 1rem;
  text-align: center;
}

.signature-preview h5 {
  margin: 0 0 0.5rem 0;
  color: #495057;
  font-size: 0.9rem;
}

.preview-image {
  max-width: 200px;
  max-height: 100px;
  border: 1px solid #dee2e6;
  border-radius: 0.375rem;
  background: white;
}

@media (max-width: 768px) {
  .signature-component {
    padding: 1rem;
  }
  
  .signature-canvas {
    width: 100%;
    max-width: 350px;
    height: 150px;
  }
  
  .signature-controls {
    flex-direction: column;
    align-items: center;
  }
  
  .btn {
    width: 100%;
    max-width: 200px;
    justify-content: center;
  }
}

/* Touch device optimizations */
@media (hover: none) and (pointer: coarse) {
  .signature-canvas {
    touch-action: none;
  }
}
</style>