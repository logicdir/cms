<?php

namespace App\Modules\Media\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Media\Http\Requests\UploadRequest;
use App\Modules\Media\Models\Media;
use App\Modules\Media\Services\MediaLibrary;
use App\Modules\Media\Services\MediaUploader;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MediaController extends Controller
{
    public function __construct(
        protected MediaUploader $uploader,
        protected MediaLibrary $library
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $folderId = $request->get('folder_id');
        $filters = $request->only(['type', 'search']);
        
        return Inertia::render('Admin/Media/Index', [
            'content' => $this->library->getContent($folderId, $filters),
            'currentFolder' => $folderId ? \App\Modules\Media\Models\MediaFolder::find($folderId) : null,
            'filters' => $filters
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UploadRequest $request)
    {
        $media = $this->uploader->upload(
            $request->file('file'),
            $request->get('folder_id')
        );

        return response()->json([
            'message' => 'File uploaded successfully',
            'media' => $media
        ]);
    }

    /**
     * Handle chunked upload.
     */
    public function uploadChunk(Request $request)
    {
        $media = $this->uploader->handleChunk(
            $request->all(),
            $request->get('folder_id')
        );

        return response()->json([
            'completed' => (bool)$media,
            'media' => $media
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Media $media)
    {
        $media->update($request->validate([
            'alt_text' => 'nullable|string|max:255',
            'title' => 'nullable|string|max:255',
            'caption' => 'nullable|string'
        ]));

        return back()->with('success', 'Media updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $ids = $request->input('ids', []);
        $this->library->deleteFiles($ids);

        return back()->with('success', 'Files deleted');
    }

    /**
     * Bulk move items.
     */
    public function move(Request $request)
    {
        $this->library->moveItems(
            $request->input('file_ids', []),
            $request->input('folder_ids', []),
            $request->input('target_folder_id')
        );

        return back()->with('success', 'Items moved');
    }
}
