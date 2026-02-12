<?php

namespace App\Modules\Media\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'file' => [
                'required',
                'file',
                'max:10240', // 10MB
                'mimetypes:image/jpeg,image/png,image/gif,image/webp,image/svg+xml,application/pdf,video/mp4,video/quicktime'
            ],
            'folder_id' => 'nullable|exists:media_folders,id'
        ];
    }
}
