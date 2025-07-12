<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>ICIR - {{ $inspection->ic_no }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .info-grid { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .info-grid td { padding: 5px; border: 1px solid #333; }
        .dimension-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .dimension-table th, .dimension-table td { border: 1px solid #333; padding: 3px; text-align: center; }
        .signature-section { margin-top: 30px; }
        .signature-box { width: 150px; height: 80px; border: 1px solid #333; display: inline-block; margin: 10px; }
        @media print { body { margin: 0; } }
    </style>
</head>
<body>
    <div class="header">
        <h2>INCOMING INSPECTION RECORD (ICIR)</h2>
    </div>

    <table class="info-grid">
        <tr>
            <td><strong>Material:</strong></td>
            <td>{{ $inspection->material }}</td>
            <td><strong>PO No.:</strong></td>
            <td>{{ $inspection->po_no }}</td>
            <td><strong>Sample Size:</strong></td>
            <td>{{ $inspection->sample_size }}</td>
        </tr>
        <tr>
            <td><strong>Supplier:</strong></td>
            <td>{{ $inspection->supplier }}</td>
            <td><strong>Issue Date:</strong></td>
            <td>{{ $inspection->issue_date }}</td>
            <td><strong>Instrument Used:</strong></td>
            <td>{{ $inspection->instrument_used }}</td>
        </tr>
        <!-- Add more rows for other fields -->
    </table>

    <h3>DIMENSION INSPECTION</h3>
    <table class="dimension-table">
        <thead>
            <tr>
                <th>NO.</th>
                <th>SPECIFICATION</th>
                <th>TOL.</th>
                <th>1</th><th>2</th><th>3</th><th>4</th><th>5</th>
                <th>6</th><th>7</th><th>8</th><th>9</th><th>10</th>
                <th>Rata-Rata</th>
                <th>Remark</th>
            </tr>
        </thead>
        <tbody>
            @foreach($inspection->dimensions as $index => $dimension)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $dimension->specification }}</td>
                <td>{{ $dimension->tolerance }}</td>
                <td>{{ $dimension->sample_1 }}</td>
                <td>{{ $dimension->sample_2 }}</td>
                <td>{{ $dimension->sample_3 }}</td>
                <td>{{ $dimension->sample_4 }}</td>
                <td>{{ $dimension->sample_5 }}</td>
                <td>{{ $dimension->sample_6 }}</td>
                <td>{{ $dimension->sample_7 }}</td>
                <td>{{ $dimension->sample_8 }}</td>
                <td>{{ $dimension->sample_9 }}</td>
                <td>{{ $dimension->sample_10 }}</td>
                <td>{{ $dimension->rata_rata }}</td>
                <td>{{ $dimension->remark }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="signature-section">
        <div style="display: flex; justify-content: space-between;">
            <div>
                <p><strong>Inspected By:</strong></p>
                @if($inspection->getInspectorSignature())
                    <img src="{{ $inspection->getInspectorSignature()->signature_data }}" width="150" height="80">
                    <p>{{ $inspection->getInspectorSignature()->user_name }}</p>
                @else
                    <div class="signature-box"></div>
                @endif
            </div>
            
            <div>
                <p><strong>Checked By:</strong></p>
                @if($inspection->getCheckerSignature())
                    <img src="{{ $inspection->getCheckerSignature()->signature_data }}" width="150" height="80">
                    <p>{{ $inspection->getCheckerSignature()->user_name }}</p>
                @else
                    <div class="signature-box"></div>
                @endif
            </div>
            
            <div>
                <p><strong>Approved By:</strong></p>
                @if($inspection->getApproverSignature())
                    <img src="{{ $inspection->getApproverSignature()->signature_data }}" width="150" height="80">
                    <p>{{ $inspection->getApproverSignature()->user_name }}</p>
                @else
                    <div class="signature-box"></div>
                @endif
            </div>
        </div>
    </div>
</body>
</html>