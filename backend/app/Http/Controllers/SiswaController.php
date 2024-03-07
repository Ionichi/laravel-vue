<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class SiswaController extends Controller
{
    public function index() {
        $data = Siswa::has('user')->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('nama_siswa', function($q) {
                return ucwords($q->nama_siswa);
            })
            ->editColumn('nama_panggilan', function($q) {
                return ucfirst($q->nama_panggilan);
            })
            ->editColumn('provinsi', function($q) {
                return ucwords($q->provinsi);
            })
            ->editColumn('kota', function ($q) {
                return ucwords($q->kota);
            })
            ->editColumn('alamat_lengkap', function ($q) {
                return ucfirst($q->alamat_lengkap);
            })
            ->editColumn('tgl_lahir', function ($q) {
                return Carbon::parse($q->tgl_lahir)->locale('id')->settings(['formatFunction' => 'translatedFormat'])->format('d F Y');
            })
            ->editColumn('status', function ($q) {
                if($q->status == 'A') {
                    return 'Aktif';
                }
                else {
                    return 'Nonaktif';
                }
            })
            ->addColumn('username', function ($q) {
                return $q->user->username;
            })
            ->addColumn('fullname', function ($q) {
                return ucwords($q->user->fullname);
            })
            ->addColumn('gender', function ($q) {
                if($q->user->gender == 'L') {
                    return 'Laki-laki';
                }
                else {
                    return 'Perempuan';
                }
            })
            ->addColumn('email', function ($q) {
                return $q->user->email;
            })
            ->addColumn('role', function ($q) {
                if($q->user->role == 'A') {
                    return 'Admin';
                }
                else if ($q->user->role == 'T') {
                    return 'Tutor';
                }
                else {
                    return 'Murid';
                }
            })
            ->addColumn('status_user', function ($q) {
                if ($q->user->status == 'A') {
                    return 'Aktif';
                } else {
                    return 'Nonaktif';
                }
            })
            ->addColumn('action', function ($q) {
                $button = '<button type="button" id="'.Crypt::encryptString($q->id).'" class="btnEdit btn btn-warning waves-effect waves-light rounded mr-1"><i class="fa fa-edit"></i></button>';
                $button .= '<button type="button" id="'.Crypt::encryptString($q->id).'" class="btnDelete btn btn-danger waves-effect waves-light rounded mr-1"><i class="fa fa-trash"></i></button>';
                return $button;
            })
            ->rawColumns(['action'])->make(true);
    }

    public function create(Request $request) {
        $validator = Validator::make($request->all(), [
            'username' => 'required|unique:users|max:50',
            'fullname' => 'required|max:200',
            'nama_panggilan' => 'required|max:50',
            'gender' => 'required|max:1',
            'tgl_lahir' => 'required|date',
            'no_wa' => 'required|min_digits: 10',
            'kota' => 'required',
            'provinsi' => 'required',
            'kode_pos' => 'required',
            'alamat_lengkap' => 'required',
            'email' => 'required|unique:users',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 422);
        }
        
        DB::beginTransaction();
        try {
            $dataUser = new User();
            $dataUser->username = $request->username;
            $dataUser->fullname = $request->fullname;
            $dataUser->gender = $request->gender;
            $dataUser->email = $request->email;
            $dataUser->password = Hash::make('12345678');
            $dataUser->role = 'M';
            $dataUser->status = 'A';
            $dataUser->save();

            $dataSiswa = new Siswa();
            $dataSiswa->user_id = $dataUser->id;
            $dataSiswa->nama_siswa = $dataUser->fullname;
            $dataSiswa->nama_panggilan = $request->nama_panggilan;
            $dataSiswa->no_wa = $request->no_wa;
            $dataSiswa->provinsi = $request->provinsi;
            $dataSiswa->kota = $request->kota;
            $dataSiswa->kode_pos = $request->kode_pos;
            $dataSiswa->alamat_lengkap = $request->alamat_lengkap;
            $dataSiswa->tgl_lahir = $request->tgl_lahir;
            $dataSiswa->status = 'A';
            $dataSiswa->save();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil menambahkan data siswa!'
            ], 200);
        }
        catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menambahkan data siswa!'
            ], 500);
        }
    }

    public function edit($id) {
        try {
            $id = Crypt::decryptString($id);
            $data = Siswa::findOrFail($id);
    
            return response()->json([
                "id" => Crypt::encryptString($data->id),
                "nama_siswa" => $data->nama_siswa,
                "nama_panggilan" => $data->nama_panggilan,
                "no_wa" => $data->no_wa,
                "provinsi" => $data->provinsi,
                "kota" => $data->kota,
                "kode_pos" => $data->kode_pos,
                "alamat_lengkap" => $data->alamat_lengkap,
                "tgl_lahir" => $data->tgl_lahir,
                "status" => $data->status,
                "username" => $data->user->username,
                "fullname" => $data->user->fullname,
                "gender" => $data->user->gender,
                "email" => $data->user->email,
            ]);
        }
        catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak ditemukan!'
            ], 404);
        }
    }

    public function update(Request $request) {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'fullname' => 'required|max:200',
            'nama_panggilan' => 'required|max:50',
            'gender' => 'required|max:1',
            'tgl_lahir' => 'required|date',
            'no_wa' => 'required|min_digits: 10',
            'kota' => 'required',
            'provinsi' => 'required',
            'kode_pos' => 'required',
            'alamat_lengkap' => 'required',
            'status' => 'required|max:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            $idSiswa = Crypt::decryptString($request->id);
            $dataSiswa = Siswa::find($idSiswa);
            
            $dataSiswa->nama_siswa = $request->fullname;
            $dataSiswa->nama_panggilan = $request->nama_panggilan;
            $dataSiswa->no_wa = $request->no_wa;
            $dataSiswa->provinsi = $request->provinsi;
            $dataSiswa->kota = $request->kota;
            $dataSiswa->kode_pos = $request->kode_pos;
            $dataSiswa->alamat_lengkap = $request->alamat_lengkap;
            $dataSiswa->tgl_lahir = $request->tgl_lahir;
            $dataSiswa->status = $request->status;
            $dataSiswa->update();

            $dataUser = User::find($dataSiswa->user_id);
            $dataUser->fullname = $request->fullname;
            $dataUser->gender = $request->gender;
            if($request->filled('password'))
                $dataUser->password = Hash::make($request->password);
            $dataUser->status = $request->status;
            $dataUser->update();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil mengubah data siswa!'
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengubah data siswa!'
            ], 500);
        }
    }

    public function destroy($id) {
        DB::beginTransaction();
        try {
            $id = Crypt::decryptString($id);
            $dataSiswa = Siswa::findOrFail($id);
            $dataUser = User::findOrFail($dataSiswa->user_id);

            if($dataSiswa->delete() && $dataUser->delete()) {
                DB::commit();
                return response()->json([
                    'status' => 'success',
                    'message' => 'Berhasil menghapus data siswa!'
                ], 200);
            }
            else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal menghapus data siswa!'
                ], 500);
            }

        }
        catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus data siswa!'
            ], 500);
        }
    }
}