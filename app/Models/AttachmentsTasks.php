<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AttachmentsTasks extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $fillable = [
        'path',
        'tasks_id',
        'user_id', 'deleted_at'
    ];


    public function usuario()
    {
        return $this->belongsTo(User::class);
    }

    public function task()
    {
        return $this->belongsTo(Tasks::class);
    }
}
