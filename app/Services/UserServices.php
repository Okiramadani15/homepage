<?php

namespace App\Services;

use Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Services\Utils\Response\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserServices{

    protected $response;

    public function __construct(Response $response){
        $this->response = $response;
    }

    public function userCreated($data){
        try{
            $rules = [
                'name' => 'required',
                'email' => 'required|unique:users|email:rfc,dns',
                'password' => 'required',
                'address' => 'required',
                'phone' => 'required|unique:users',
                'id_gender' => 'required',
                'id_position' => 'required',
                'photo' => 'mimes:jpg,png,jpeg',
            ];
    
            $messages = [
                'name.required' => 'Tidak boleh kosong',
                'email.required' => 'Tidak boleh kosong',
                'email.unique' => 'Email sudah terdaftar',
                'email.email' => 'Format email salah',
                'password.required' => 'Tidak boleh kosong',
                'address.required' => 'Tidak boleh kosong',
                'phone.required' => 'Tidak boleh kosong',
                'phone.unique' => 'Nomor Handphone sudah terdaftar',
                'id_gender.required' => 'Tidak boleh kosong',
                'id_position.required' => 'Tidak boleh kosong',
                'photo.mimes' => 'Format foto harus jpg,png,jpeg',
            ];
    
            $validator = Validator::make($data->all(), $rules, $messages);
            if($validator->fails()){
                return response()->json([
                    'status' => 'fail',
                    'message' => $validator->errors()->first(),
                ]);
            }

            
            if($data->id_gender == '1'){
                $fileName = "/assets/illustrations/male.jpg";
            }else{
                $fileName = "/assets/illustrations/female.jpg";
            }
            
            $foto = $data->photo;
            if ($foto) {
                $fileName = '/assets/photo/' . $data->phone . '_' . time() . '.' . $foto->getClientOriginalExtension();
                $foto->move(public_path('/assets/photo/'), $fileName);
            }
            
            $create = new User;
            $create->name = $data->name;
            $create->email = $data->email;
            $create->password = bcrypt($data->password);
            $create->address = $data->address;
            $create->phone = $data->phone;
            $create->id_gender = $data->id_gender;
            $create->id_position = $data->id_position;
            $create->photo = $fileName;
            $create->level = 1;
            $create->save();

            if($create){
                return ['status' => 'success', 'message' => 'Berhasil menambah user baru'];
            }else{
                return ['status' => 'fail', 'message' => 'failed'];
            }

        }catch(Exception $e){
            return $e->getMessage();
        }
    }

    public function getProfile(){
        try{
            $data = User::where('id', Auth::user()->id)->with([
                'position' => function($q){
                    $q->select('id', 'name');
                }
            ])->first();
            
            return response()->json([
                'status' => 'success',
                'data' => $data,
                'message' => 'success'
            ]);
        }catch(Exception $e){
            return $e->getMessage();
        }
    }

    public function userLogin($data){
        try{
            $user = User::where('email', $data->email)->first();
            $token = $user->createToken($data->device_name)->plainTextToken;

            if(!$user){
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Email tidak terdaftar'
                ], 200);
            }else{
                if($user && Hash::check($data->password, $user->password)){
                    return response()->json([
                        'status' => 'success',
                        'data' => $token,
                        'message' => 'success'
                    ]);
                }else{
                    return response()->json([
                        'status' => 'fail',
                        'message' => 'Email atau Password Salah'
                    ], 200);
                }
            }
        }catch(Exception $e){
            return $e->getMessage();
        }
    }

    public function userLogout($data){
        $user = Auth::user();
        $user->tokens()->where('id', $user->currentAccessToken()->id)->delete();

        if($user){
            return ['status' => "success", 'message' => 'Berhasil logout'];
        }else{
            return ['status' => "fail", 'message' => 'Failed'];
        }
    }

    public function detailUser($data){
        try{
            $data = User::find($data->id);
            
            if($data){
                return response()->json([
                    'status' => 'success',
                    'data' => $data,
                    'message' => 'success'
                ]);
            }else{
                return response()->json([
                    'status' => 'fail',
                    'data' => [],
                    'message' => 'User tidak ditemukan'
                ]);
            }
        }catch(Exception $e){
            return $e->getMessage();
        }
    }

    public function updateUser($data){
        try{
            $user = User::find($data->id);

            $rules = [
                'name' => 'required',
                'email' => [
                    'required',
                    'email:rfc,dns',
                    Rule::unique('users', 'email')->ignore($user->id, 'id')
                ],
                'address' => 'required',
                'phone' => [
                    'required',
                    Rule::unique('users')->ignore($data->id, 'id'),
                ],
                'id_gender' => 'required',
                'id_position' => 'required',
                'photo' => 'image|mimes:jpg,png,jpeg',
            ];
    
            $messages = [
                'name.required' => 'Tidak boleh kosong',
                'email.required' => 'Tidak boleh kosong',
                'email.unique' => 'Email sudah digunakan',
                'email.email' => 'Format email salah',
                'address.required' => 'Tidak boleh kosong',
                'phone.required' => 'Tidak boleh kosong',
                'phone.unique' => 'Nomor Handphone sudah digunakan',
                'id_gender.required' => 'Tidak boleh kosong',
                'id_position.required' => 'Tidak boleh kosong',
                'photo.image' => 'File harus gambar',
                'photo.mimes' => 'Format gambar harus jpg,png,jpeg',
            ];
    
            $validator = Validator::make($data->all(), $rules, $messages);
            if($validator->fails()){
                return response()->json([
                    'status' => 'fail',
                    'message' => $validator->errors()->first(),
                ]);
            }

            
            $fileName = $user->photo;
            
            $foto = $data->photo;
            if ($foto) {
                $fileName = '/assets/photo/' . $data->phone . '_' . time() . '.' . $foto->getClientOriginalExtension();
                $foto->move(public_path('/assets/photo/'), $fileName);
            }
            
            $user->name = $data->name;
            $user->email = $data->email;
            if($data->password){
                $user->password = bcrypt($data->password);
            }
            $user->address = $data->address;
            $user->phone = $data->phone;
            $user->id_gender = $data->id_gender;
            $user->id_position = $data->id_position;
            $user->photo = $fileName;
            $user->save();

            if($user){
                return ['status' => 'success', 'message' => 'Berhasil mengubah data user'];
            }else{
                return ['status' => 'fail', 'message' => 'failed'];
            }

        }catch(Exception $e){
            return $e->getMessage();
        }
    }

    public function deleteUser($data){
        try{
            $delete = User::where('id', $data->id)->delete();

            if($delete){
                return response()->json([
                    'status' => 'success',
                    'message' => 'Berhasil Menghapus user'
                ]);
            }else{
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Gagal menghapus user'
                ]);
            }
        }catch(Exception $e){
            return response()->json([
                'status' => 'fail',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function getAllUser($data){
        try{
            $users = new User;
            $users = $users->orderBy('name', 'asc');
            $users = $users->where('name', 'like', '%'.$data->search.'%');
            $users = $users->where('email', '!=', 'superadmin@gmail.com');
            $users = $users->with([
                'gender' => function($q){
                    $q->select('id', 'name');
                },
                'position' => function($q){
                    $q->select('id', 'name');
                }
            ]);
            $users = $users->paginate($data->limit)->onEachSide(1)->toArray();
            
            $listPage = [];
            $i = 1;
            foreach($users['links'] as $value){
                $explodeUrl = explode("page=", $value['url']);

                if($i == 1){
                    $tmpUsers = [
                        'page' => $users['current_page'] != 1 ? $users['current_page'] - 1 : 1,
                        'label' => "Previous",
                        'active' => $value['active'],
                    ];
                }elseif($i == $users['last_page'] + 2){
                    $tmpUsers = [
                        'page' => $users['current_page'] < $users['last_page'] ? $users['current_page'] + 1 : $users['current_page'],
                        'label' => "Next",
                        'active' => $value['active'],
                    ];
                }elseif($value['url'] != null){
                    $tmpUsers = [
                        'page' => $explodeUrl[1],
                        'label' => $value['label'],
                        'active' => $value['active'],
                    ];
                }else{
                    $tmpUsers = [
                        'page' => "",
                        'label' => "...",
                        'active' => false
                    ];
                }
                array_push($listPage, $tmpUsers);
                $i += 1;
            }
            
            if($users){
                return response()->json([
                    'status' => 'success',
                    'pagination' => [
                        'current_page' => $users['current_page'],
                        'last_page' => $users['last_page'],
                        'previous_page' => $users['current_page'] != 1 ? $users['current_page'] - 1 : 1,
                        'next_page' => $users['current_page'] < $users['last_page'] ? $users['current_page'] + 1 : $users['current_page'],
                        'per_page' => $users['per_page'],
                        'total' => $users['total'],
                        'from' => $users['from'],
                        'to' => $users['to'],
                        'list_page' => $listPage,
                    ],
                    'data' => $users['data'],
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
}
