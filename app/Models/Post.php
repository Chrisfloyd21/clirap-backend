<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // <--- ICI
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory; // <--- ET ICI

    protected $fillable = [
        'title_it', 'slug', 'content_it', 'image_url', 'excerpt_it', 'user_id', 'is_published'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}