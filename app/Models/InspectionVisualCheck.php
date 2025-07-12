<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InspectionVisualCheck extends Model
{
    use HasFactory;

    protected $fillable = [
        'inspection_id', 'check_type', 'accepted', 'rejected', 'remark'
    ];

    protected $casts = [
        'accepted' => 'boolean',
        'rejected' => 'boolean',
    ];

    public function inspection()
    {
        return $this->belongsTo(Inspection::class);
    }
}