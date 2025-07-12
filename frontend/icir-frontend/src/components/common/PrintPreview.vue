<template>
  <div class="print-preview-modal" @click.self="$emit('close')">
    <div class="modal-content">
      <div class="modal-header">
        <h3>Print Preview</h3>
        <button @click="$emit('close')" class="close-btn">&times;</button>
      </div>
      
      <div class="modal-body">
        <div class="print-preview" id="print-content">
          <!-- ICIR Header -->
          <div class="print-header">
            <h2>INCOMING INSPECTION RECORD (ICIR)</h2>
          </div>

          <!-- Information Table -->
          <table class="print-info-table">
            <tr>
              <td><strong>Material:</strong></td>
              <td>_________________</td>
              <td><strong>PO No.:</strong></td>
              <td>_________________</td>
              <td><strong>Sample Size:</strong></td>
              <td>_________________</td>
            </tr>
            <tr>
              <td><strong>Supplier:</strong></td>
              <td>_________________</td>
              <td><strong>Issue Date:</strong></td>
              <td>_________________</td>
              <td><strong>Instrument Used:</strong></td>
              <td>_________________</td>
            </tr>
            <tr>
              <td><strong>Description:</strong></td>
              <td>_________________</td>
              <td><strong>Location:</strong></td>
              <td>_________________</td>
              <td><strong>Nomer Instrument:</strong></td>
              <td>_________________</td>
            </tr>
            <tr>
              <td><strong>IC No.:</strong></td>
              <td>_________________</td>
              <td><strong>Lot Size Qty:</strong></td>
              <td>_________________</td>
              <td><strong>Qty Good:</strong></td>
              <td>_________________</td>
            </tr>
            <tr>
              <td><strong>Unit:</strong></td>
              <td>_________________</td>
              <td><strong>Data OutGoing:</strong></td>
              <td>_________________</td>
              <td><strong>Qty No Good:</strong></td>
              <td>_________________</td>
            </tr>
            <tr>
              <td><strong>COA Data:</strong></td>
              <td>_________________</td>
              <td><strong>ICP Data:</strong></td>
              <td>_________________</td>
              <td><strong>AQL:</strong></td>
              <td>_________________</td>
            </tr>
            <tr>
              <td colspan="4"><strong>ACC / REJECT:</strong></td>
              <td colspan="2">_________________</td>
            </tr>
          </table>

          <!-- Drawing Section -->
          <div class="drawing-section">
            <strong>DRAWING / ILLUSTRATION</strong>
            <div class="drawing-box"></div>
          </div>

          <!-- Sample Size Section -->
          <div class="sample-size-section">
            <strong>SAMPLE SIZE: _________ (FOLLOW STANDARD SAMPLING CHECK)</strong>
          </div>

          <!-- Dimension Table -->
          <table class="print-dimension-table">
            <thead>
              <tr>
                <th rowspan="2">NO.</th>
                <th rowspan="2">SPECIFICATION</th>
                <th rowspan="2">TOL.</th>
                <th colspan="10">INSPECTION DATA (DIMENSION)</th>
                <th rowspan="2">Rata-Rata</th>
                <th rowspan="2">Remark</th>
              </tr>
              <tr>
                <th v-for="i in 10" :key="i">{{ i }}</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>A.1</td>
                <td>THICKNESS</td>
                <td></td>
                <td v-for="i in 10" :key="i"></td>
                <td></td>
                <td></td>
              </tr>
              <tr>
                <td>A.2</td>
                <td>WIDTH</td>
                <td></td>
                <td v-for="i in 10" :key="i"></td>
                <td></td>
                <td></td>
              </tr>
              <tr>
                <td>A.3</td>
                <td>LENGTH</td>
                <td></td>
                <td v-for="i in 10" :key="i"></td>
                <td></td>
                <td></td>
              </tr>
              <tr>
                <td>A.4</td>
                <td>DIAMETER</td>
                <td></td>
                <td v-for="i in 10" :key="i"></td>
                <td></td>
                <td></td>
              </tr>
              <tr>
                <td>A.5</td>
                <td>OTHER</td>
                <td></td>
                <td v-for="i in 10" :key="i"></td>
                <td></td>
                <td></td>
              </tr>
            </tbody>
          </table>

          <!-- Visual Check Section -->
          <div class="visual-check-print">
            <h4>VISUAL CHECK</h4>
            <div class="visual-check-grid">
              <div class="visual-column">
                <table class="visual-table">
                  <thead>
                    <tr>
                      <th>B. VISUAL CHECK</th>
                      <th>ACCEPTED</th>
                      <th>REJECTED</th>
                      <th>REMARK</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr><td>B.1 DIRTY (Oil, Dust)</td><td>☐</td><td>☐</td><td></td></tr>
                    <tr><td>B.2 WRONG COLOR</td><td>☐</td><td>☐</td><td></td></tr>
                    <tr><td>B.3 WRINKLE/BUBBLE</td><td>☐</td><td>☐</td><td></td></tr>
                    <tr><td>B.4 SCRATCHES/FLASH</td><td>☐</td><td>☐</td><td></td></tr>
                    <tr><td>B.5 TEAR/TORN</td><td>☐</td><td>☐</td><td></td></tr>
                  </tbody>
                </table>
              </div>
              
              <div class="visual-column">
                <table class="visual-table">
                  <thead>
                    <tr>
                      <th>B. VISUAL CHECK</th>
                      <th>ACCEPTED</th>
                      <th>REJECTED</th>
                      <th>REMARK</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr><td>B.6 BROKEN/DAMAGE</td><td>☐</td><td>☐</td><td></td></tr>
                    <tr><td>B.7 CHECK ICP</td><td>☐</td><td>☐</td><td></td></tr>
                    <tr><td>B.8 CHECK COA</td><td>☐</td><td>☐</td><td></td></tr>
                    <tr><td>B.9 CHECK SAFETY</td><td>☐</td><td>☐</td><td></td></tr>
                    <tr><td>B.10 OTHER</td><td>☐</td><td>☐</td><td></td></tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <!-- Signature Section -->
          <div class="signature-section-print">
            <table class="signature-table">
              <tr>
                <td>
                  <strong>DISPOSITION / QC STAMP</strong>
                  <div class="signature-box"></div>
                </td>
                <td>
                  <strong>INSPECTED BY</strong>
                  <div class="signature-box"></div>
                  <div class="signature-line">Name: _______________</div>
                  <div class="signature-line">Date: _______________</div>
                </td>
                <td>
                  <strong>CHECKED BY</strong>
                  <div class="signature-box"></div>
                  <div class="signature-line">Name: _______________</div>
                  <div class="signature-line">Date: _______________</div>
                </td>
                <td>
                  <strong>APPROVED BY</strong>
                  <div class="signature-box"></div>
                  <div class="signature-line">Name: _______________</div>
                  <div class="signature-line">Date: _______________</div>
                </td>
                <td>
                  <strong>SUPPLIER LOT NO. / IDENTITY</strong>
                  <div class="supplier-box"></div>
                </td>
              </tr>
            </table>
          </div>
        </div>
      </div>
      
      <div class="modal-footer">
        <button @click="printDocument" class="btn btn-primary">Print</button>
        <button @click="$emit('close')" class="btn btn-secondary">Close</button>
      </div>
    </div>
  </div>
</template>

<script setup>
defineEmits(['close'])

const printDocument = () => {
  const printContent = document.getElementById('print-content').innerHTML
  const printWindow = window.open('', '_blank')
  
  printWindow.document.write(`
    <!DOCTYPE html>
    <html>
    <head>
      <title>ICIR - Blank Form</title>
      <style>
        body { font-family: Arial, sans-serif; font-size: 12px; margin: 0; padding: 20px; }
        .print-header { text-align: center; margin-bottom: 20px; }
        .print-info-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .print-info-table td { border: 1px solid #000; padding: 8px; }
        .drawing-section { margin: 20px 0; }
        .drawing-box { border: 1px solid #000; height: 100px; margin-top: 10px; }
        .sample-size-section { margin: 20px 0; }
        .print-dimension-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .print-dimension-table th, .print-dimension-table td { border: 1px solid #000; padding: 5px; text-align: center; }
        .visual-check-print { margin: 20px 0; }
        .visual-check-grid { display: flex; gap: 20px; }
        .visual-column { flex: 1; }
        .visual-table { width: 100%; border-collapse: collapse; }
        .visual-table th, .visual-table td { border: 1px solid #000; padding: 5px; }
        .signature-section-print { margin-top: 30px; }
        .signature-table { width: 100%; border-collapse: collapse; }
        .signature-table td { border: 1px solid #000; padding: 10px; text-align: center; vertical-align: top; }
        .signature-box { border: 1px solid #000; height: 60px; margin: 10px 0; }
        .supplier-box { border: 1px solid #000; height: 60px; margin: 10px 0; }
        .signature-line { margin: 5px 0; }
        @media print { body { margin: 0; } }
      </style>
    </head>
    <body>
      ${printContent}
    </body>
    </html>
  `)
  
  printWindow.document.close()
  printWindow.print()
}
</script>

<style scoped>
.print-preview-modal {
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
  border-radius: 8px;
  width: 90%;
  max-width: 1000px;
  max-height: 90%;
  display: flex;
  flex-direction: column;
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 20px;
  border-bottom: 1px solid #dee2e6;
}

.close-btn {
  background: none;
  border: none;
  font-size: 24px;
  cursor: pointer;
}

.modal-body {
  flex: 1;
  overflow-y: auto;
  padding: 20px;
}

.modal-footer {
  padding: 20px;
  border-top: 1px solid #dee2e6;
  display: flex;
  justify-content: flex-end;
  gap: 10px;
}

.btn {
  padding: 10px 20px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.btn-primary {
  background-color: #007bff;
  color: white;
}

.btn-secondary {
  background-color: #6c757d;
  color: white;
}

/* Print Preview Styles */
.print-preview {
  font-family: Arial, sans-serif;
  font-size: 12px;
  line-height: 1.4;
}

.print-header {
  text-align: center;
  margin-bottom: 20px;
}

.print-info-table {
  width: 100%;
  border-collapse: collapse;
  margin-bottom: 20px;
}

.print-info-table td {
  border: 1px solid #000;
  padding: 8px;
  vertical-align: top;
}

.drawing-section {
  margin: 20px 0;
}

.drawing-box {
  border: 1px solid #000;
  height: 100px;
  margin-top: 10px;
}

.sample-size-section {
  margin: 20px 0;
  font-weight: bold;
}

.print-dimension-table {
  width: 100%;
  border-collapse: collapse;
  margin-bottom: 20px;
}

.print-dimension-table th,
.print-dimension-table td {
  border: 1px solid #000;
  padding: 5px;
  text-align: center;
}

.visual-check-print h4 {
  margin-bottom: 15px;
}

.visual-check-grid {
  display: flex;
  gap: 20px;
}

.visual-column {
  flex: 1;
}

.visual-table {
  width: 100%;
  border-collapse: collapse;
}

.visual-table th,
.visual-table td {
  border: 1px solid #000;
  padding: 5px;
  text-align: left;
}

.signature-section-print {
  margin-top: 30px;
}

.signature-table {
  width: 100%;
  border-collapse: collapse;
}

.signature-table td {
  border: 1px solid #000;
  padding: 10px;
  text-align: center;
  vertical-align: top;
}

.signature-box,
.supplier-box {
  border: 1px solid #000;
  height: 60px;
  margin: 10px 0;
}

.signature-line {
  margin: 5px 0;
  text-align: left;
}
</style>