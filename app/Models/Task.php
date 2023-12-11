<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    use HasUuids;
    protected $fillable = [
        "task_name",
        "priority",
        "project_id",
    ];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
}
