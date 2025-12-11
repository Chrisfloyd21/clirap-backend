<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // <--- IMPORT IMPORTANT
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory; // <--- LIGNE MANQUANTE QUI CAUSE L'ERREUR

    protected $fillable = [
        'title_it', 
        'description_it', 
        'category', 
        'image_url', 
        'is_completed'
    ];
}