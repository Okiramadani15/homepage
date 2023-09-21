<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class CalendarRequest extends FormRequest
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
        if($this->isMethod('post')){
            return [
                'description' => 'required',
                'from' => 'required',
                'to' => 'required',
                'id_semester' => 'required',
            ];
        }

        if($this->isMethod('put')){
            return [
                'id' => 'required',
                'description' => 'required',
                'description' => 'required',
                'from' => 'required',
                'to' => 'required',
                'id_semester' => 'required',
            ];
        }
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => "fail",
            'data' => $validator->errors(),
            'message' => $validator->errors()->first(),
        ]));
    }

    public function messages()
    {
        return [
            'id.required' => 'Tidak boleh kosong',
            'description.required' => 'Keterangan tidak boleh kosong',
            'from.required' => 'Tanggal awal tidak boleh kosong',
            'to.required' => 'Tanggal akhir tidak boleh kosong',
            'id_semester.required' => 'Semester tidak boleh kosong',
        ];
    }

}
