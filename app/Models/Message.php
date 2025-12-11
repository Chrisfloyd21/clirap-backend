<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // <--- ICI
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory; // <--- ET ICI

    protected $fillable = ['name', 'email', 'subject', 'message', 'is_read'];
}