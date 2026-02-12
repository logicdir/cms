<?php

namespace App\Modules\Installer\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SiteConfigRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'site_name' => 'required|string|max:255',
            'site_url' => 'required|url|max:255',
        ];
    }
}
