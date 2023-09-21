<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class ActivityUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => 'required',
            'title' => 'required',
            'description' => 'required',
        ];
    }

    public function messages(){
        return [
            'id.required' => 'Data tidak boleh kosong',
            'title.required' => 'Judul tidak boleh kosong',
            'description.required' => 'Keterangan tidak boleh kosong',
        ];
    }

    public function failedValidation(Validator $validator){
        throw new HttpResponseException(response()->json([
            'status' => "fail",
            'data' => $validator->errors(),
            'message' => $validator->errors()->first(),
        ]));
    }
}
