<?php

namespace App\Services\Backoffice;

use App\Models\Backoffice\Banner;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use File;
use Intervention\Image\ImageManagerStatic as Image;

class BannerService{
    public function index(){
        try{
            $banner = Banner::orderBy('id', 'asc')->get();

            if($banner){
                return response()->json([
                    'status' => 'success',
                    'data' => $banner,
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

    public function updateBanner($data){
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


            $detail = Banner::find($data->id)->first();

            $tmpFilePath = $detail->path;

            $foto = $data->photo;

            if ($foto) {
                $fileName = '/assets/banner/' . '_' . time() . '.' . $foto->getClientOriginalExtension();
                $foto->move(public_path('/assets/banner/'), $fileName);
            }
            $detail->path = $fileName;
            $detail->save();

            if (File::exists(public_path($tmpFilePath))) {
                File::delete(public_path($tmpFilePath));
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