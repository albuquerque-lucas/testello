<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadFreightCsvRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'csv_file' => 'required|array',
            'csv_file.*' => 'file|mimes:csv,txt|max:50240',
        ];
    }

    public function messages()
    {
        return [
            'csv_file.required' => 'Você deve enviar pelo menos um arquivo CSV.',
            'csv_file.array' => 'O campo csv_file deve ser um array de arquivos.',
            'csv_file.*.file' => 'Cada item deve ser um arquivo.',
            'csv_file.*.mimes' => 'Cada arquivo deve ser do tipo CSV ou TXT.',
            'csv_file.*.max' => 'Cada arquivo não deve exceder 50 MB.',
        ];
    }
}
