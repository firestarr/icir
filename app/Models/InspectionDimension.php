<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InspectionDimension extends Model
{
    use HasFactory;

    protected $fillable = [
        'inspection_id', 'dimension_type', 'specification', 'tolerance',
        'sample_1', 'sample_2', 'sample_3', 'sample_4', 'sample_5',
        'sample_6', 'sample_7', 'sample_8', 'sample_9', 'sample_10',
        'rata_rata', 'remark'
    ];

    public function inspection()
    {
        return $this->belongsTo(Inspection::class);
    }

    protected static function boot()
    {
        parent::boot();
        
        static::saving(function ($model) {
            $samples = collect([
                $model->sample_1, $model->sample_2, $model->sample_3,
                $model->sample_4, $model->sample_5, $model->sample_6,
                $model->sample_7, $model->sample_8, $model->sample_9,
                $model->sample_10
            ])->filter()->map(function ($value) {
                return (float) $value;
            });

            if ($samples->count() > 0) {
                $model->rata_rata = round($samples->avg(), 3);
            }
        });
    }
}