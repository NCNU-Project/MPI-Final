<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Upload extends Model
{
    use HasFactory;

    protected $fillable = [
        'upload_path',
        'filename',
    ];
    public function file_status() {
        return $this->belongsTo(FileStatus::class);
    }
}
