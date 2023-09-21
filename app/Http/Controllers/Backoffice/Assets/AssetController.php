<?php

namespace App\Http\Controllers\Backoffice\Assets;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Backoffice\Assets\AssetService;
use App\Imports\AssetImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;

class AssetController extends Controller
{
    protected $asset;

    public function __construct(AssetService $assetService){
        $this->asset = $assetService;
    }

    public function index(Request $request){
        return $this->asset->getAllAssets($request);
    }

    public function listAsset(Request $request){
        return $this->asset->listAsset($request);
    }

    public function create(Request $request){
        return $this->asset->createAsset($request);
    }

    public function detail(Request $request){
        return $this->asset->detailAsset($request);
    }

    public function update(Request $request){
        return $this->asset->updateAsset($request);
    }

    public function delete(Request $request){
        return $this->asset->deleteAsset($request);
    }

    public function exportAsset(Request $request){
        return $this->asset->exportAsset($request);
    }

    public function importAsset(Request $request) 
	{
        try{
            $rules = [
                'file' => 'required|mimes:xls,xlsx|max:2048',
            ];
    
            $messages = [
                'file.required' => 'File tidak boleh kosong',
                'file.mimes' => 'Format file harus xls,xlsx',
                'file.max' => 'Maksimal 2MB',
            ];
    
            $validator = Validator::make($request->all(), $rules, $messages);
    
            if($validator->fails()){
                return response()->json([
                    'status' => 'fail',
                    'error' => $validator->errors(),
                    'message' => $validator->errors()->first()
                ], 403);
            }else{
                $file = $request->file('file');
                $nama_file = rand().$file->getClientOriginalName();
                $file->move(public_path('/assets/import/'), $nama_file);
                $import = Excel::import(new AssetImport, public_path('/assets/import/'.$nama_file));
                
                if($import){
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Success'
                    ]);
                }else{
                    return response()->json([
                        'status' => 'fail',
                        'message' => 'Gagal mengimport data'
                    ]);
                }
            }
        }catch(Exception $e){
            return response()->json([
                'status' => 'fail',
                'message' => $e->getMessage()
            ]);
        }
	}
}
