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
    public function index()
    {
        $data = Siswa::has('user')->orderBy('status', 'ASC')->get()
            ->map(function($data) {
                return [
                    'id' => Crypt::encryptString($data->id),
                    'user_id' => $data->user_id,
                    'nama_siswa' => ucwords($data->nama_siswa),
                    'nama_panggilan' => ucwords($data->nama_panggilan),
                    'no_wa' => $data->no_wa,
                    'provinsi' => ucwords($data->provinsi),
                    'kota' => ucwords($data->kota),
                    'kode_pos' => $data->kode_pos,
                    'alamat_lengkap' => ucfirst($data->alamat_lengkap),
                    'tgl_lahir' => Carbon::parse($data->tgl_lahir)->locale('id')->settings(['formatFunction' => 'translatedFormat'])->format('d F Y'),
                    'status' => ($data->status == 'A') ? 'Aktif' : 'Nonaktif',
                    'username' => $data->user->username,
                    'fullname' => ucwords($data->user->fullname),
                    'gender' => ($data->user->gender == 'L') ? 'Laki-laki' : 'Perempuan',
                    'email' => $data->user->email,
                ];
            });
        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil mengambil data siswa...',
            'data' => $data,
        ], 200);
    }

    public function create(Request $request)
    {
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
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menambahkan data siswa!',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    public function edit($id)
    {
        try {
            $id = Crypt::decryptString($id);
            $data = Siswa::findOrFail($id);
    
            return response()->json([
                "status" => "success",
                "message" => "Data ditemukan!",
                'data' => [
                    "id" => Crypt::encryptString($data->id),
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
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak ditemukan!',
                'data' => $e->getMessage(),
            ], 404);
        }
    }

    public function update(Request $request)
    {
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
            $dataSiswa = Siswa::findOrFail($idSiswa);
            
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

            $dataUser = User::findOrFail($dataSiswa->user_id);
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
                'message' => 'Gagal mengubah data siswa!',
                'data' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
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
                'message' => 'Gagal menghapus data siswa!',
                'data' => $e->getMessage()
            ], 500);
        }
    }
}