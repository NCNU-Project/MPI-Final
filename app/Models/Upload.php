<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Upload extends Model
{
    use HasFactory;

    protected $fillable = [
        'filename',
        'ct_digest',
        'uuid',
        'elapsed_time',
    ];

    public function upload_path()
    {
        return '/storage/videos/' . $this->uuid . '.mp4';
    }

    public function processed_path()
    {
        return '/storage/videos/' . $this->uuid . '-processed.mp4';
    }

    public function file_status()
    {
        return $this->belongsTo(FileStatus::class);
    }
}
