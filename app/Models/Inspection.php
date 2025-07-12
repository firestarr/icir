<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inspection extends Model
{
    use HasFactory;

    protected $fillable = [
        'material', 'po_no', 'sample_size', 'supplier', 'issue_date',
        'instrument_used', 'description', 'location', 'nomer_instrument',
        'ic_no', 'lot_size_qty', 'qty_good', 'unit', 'data_outgoing',
        'qty_no_good', 'coa_data', 'icp_data', 'aql', 'acc_reject',
        'supplier_lot_no', 'status'
    ];

    protected $casts = [
        'issue_date' => 'date',
    ];

    public function dimensions()
    {
        return $this->hasMany(InspectionDimension::class);
    }

    public function visualChecks()
    {
        return $this->hasMany(InspectionVisualCheck::class);
    }

    public function signatures()
    {
        return $this->hasMany(InspectionSignature::class);
    }

    public function getInspectorSignature()
    {
        return $this->signatures()->where('role', 'inspector')->first();
    }

    public function getCheckerSignature()
    {
        return $this->signatures()->where('role', 'checker')->first();
    }

    public function getApproverSignature()
    {
        return $this->signatures()->where('role', 'approver')->first();
    }
}