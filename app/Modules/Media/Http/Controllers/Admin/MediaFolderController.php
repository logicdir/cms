<?php

namespace App\Modules\Media\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Media\Models\MediaFolder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MediaFolderController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:media_folders,id'
        ]);

        $folder = MediaFolder::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'parent_id' => $validated['parent_id'],
            'created_by' => auth()->id() ?? 1
        ]);

        return back()->with('success', "Folder '{$folder->name}' created");
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MediaFolder $folder)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $folder->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name'])
        ]);

        return back()->with('success', 'Folder renamed');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MediaFolder $folder)
    {
        // Recursively deletes children and media due to DB cascade
        $folder->delete();

        return back()->with('success', 'Folder deleted');
    }
}
