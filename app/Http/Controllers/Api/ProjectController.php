<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller {
    // Lecture (Public)
    public function index() {
        return Project::latest()->paginate(9); // Pagination pour l'écoconception
    }

    // Création (Admin)
    public function store(Request $request) {
        $data = $request->validate([
            'title_it' => 'required',
            'description_it' => 'required',
            'category' => 'required',
            'image_url' => 'nullable|url'
        ]);
        return Project::create($data);
    }

    // Suppression (Admin)
    public function destroy(Project $project) {
        $project->delete();
        return response()->noContent();
    }
}