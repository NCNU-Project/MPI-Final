<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
    ];

    public function uploads(){
        return $this->hasMany(Upload::class);
    }
}
