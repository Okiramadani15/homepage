<?php

namespace App\Services\Backoffice;

use App\Models\Backoffice\Galery;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use File;
use Intervention\Image\ImageManagerStatic as Image;

class GaleryService{
    public function index(){
        try{
            $galery = Galery::orderBy('id', 'asc')->get();

            if($galery){
                return response()->json([
                    'status' => 'success',
                    'data' => $galery,
                    'message' => 'Success'
                ]);
            }else{
                return response()->json([
                    'status' => 'fail',
                    'data' => [],
                    'message' => 'Failed'
                ]);
            }
        }catch(Exception $e){
            return response()->json([
                'status' => 'fail',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function updateGalery($data){
        try{
            $rules = [
                'id' => 'required',
                'photo' => 'required|mimes:jpg,png,jpeg|max:2048',
            ];
    
            $messages = [
                'id.required' => 'Data tidak boleh kosong',
                'photo.required' => 'Tidak boleh kosong',
                'photo.mimes' => 'Format foto harus jpg,png,jpeg',
                'photo.max' => 'Maksimal 2MB',
            ];
    
            $validator = Validator::make($data->all(), $rules, $messages);
            if($validator->fails()){
                return response()->json([
                    'status' => 'fail',
                    'message' => $validator->errors()->first(),
                ]);
            }


            $detail = Galery::where('id', $data->id)->first();

            $tmpFilePath = $detail->path;
            $tmpFilePreview = $detail->preview;

            $foto = $data->photo;
            $fotoPreview = $data->preview;
            if ($foto) {
                $fileName = '/assets/galery/' . '_' . time() . '.' . $foto->getClientOriginalExtension();
                $foto->move(public_path('/assets/galery/'), $fileName);

                $previewName = '/assets/galery/preview/' . 'preview_' . time() . '.' . $fotoPreview->getClientOriginalExtension();
                $fotoPreview->move(public_path('/assets/galery/preview/'), $previewName);

                $img = Image::make(public_path($previewName));
                $img->resize(720, 405);
                $img->save(public_path($previewName));
            }
            $detail->path = $fileName;
            $detail->preview = $previewName;
            $detail->save();

            if (File::exists(public_path($tmpFilePath))) {
                File::delete(public_path($tmpFilePath));
            }

            if (File::exists(public_path($tmpFilePreview))) {
                File::delete(public_path($tmpFilePreview));
            }

            if($detail){
                return response()->json([
                    'status' => 'success',
                    'message' => 'Success'
                ]);
            }else{
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Failed'
                ]);
            }
        }catch(Exception $e){
            return response()->json([
                'status' => 'fail',
                'message' => $e->getMessage()
            ]);
        }
    }
}