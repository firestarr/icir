<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InspectionSignature extends Model
{
    use HasFactory;

    protected $fillable = [
        'inspection_id', 'role', 'signature_data', 'user_name', 'signed_at'
    ];

    protected $casts = [
        'signed_at' => 'datetime',
    ];

    public function inspection()
    {
        return $this->belongsTo(Inspection::class);
    }
}