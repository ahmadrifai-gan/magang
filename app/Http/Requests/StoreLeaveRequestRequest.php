<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class StoreLeaveRequestRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->isEmployee();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:500',
            'attachment' => [
                'nullable',
                File::types(['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'])
                    ->max(5 * 1024), // 5MB max
            ],
        ];
    }

    /**
     * Get custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'start_date.required' => 'Tanggal mulai cuti wajib diisi',
            'start_date.date' => 'Tanggal mulai cuti harus format tanggal yang valid',
            'start_date.after_or_equal' => 'Tanggal mulai cuti tidak boleh di masa lalu',
            'end_date.required' => 'Tanggal akhir cuti wajib diisi',
            'end_date.date' => 'Tanggal akhir cuti harus format tanggal yang valid',
            'end_date.after_or_equal' => 'Tanggal akhir cuti tidak boleh lebih awal dari tanggal mulai',
            'reason.required' => 'Alasan cuti wajib diisi',
            'reason.max' => 'Alasan cuti maksimal 500 karakter',
            'attachment.file' => 'File harus berupa dokumen atau gambar',
            'attachment.max' => 'Ukuran file maksimal 5MB',
        ];
    }
}
