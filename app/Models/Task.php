<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['title', 'description', 'priority', 'user_email', 'due_date'];

    protected $casts = [
        'due_date' => 'datetime',
    ];

    // Validation rules
    public static array $validationRules = [
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'priority' => 'required|in:low,medium,high',
        'due_date' => 'nullable|date',
        'user_email' => 'required|email|max:255',
    ];
  
}
