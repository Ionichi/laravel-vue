<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Tutor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class CourseController extends Controller
{
    public function index()
    {
        $data = Course::has('tutor')->get()
            ->map(function($data) {
                return [
                    'id' => Crypt::encryptString($data->id),
                    'nama_kursus' => ucwords($data->nama_kursus),
                    'harga' => 'Rp ' . number_format($data->harga),
                    'status' => ($data->status == 'A') ? 'Aktif' : 'Nonaktif',
                    'nama_tutor' => ucwords($data->tutor->nama_tutor),
                    'username' => $data->tutor->user->username,
                    'gender' => ($data->tutor->user->gender == 'L') ? 'Laki-laki' : 'Perempuan',
                    'no_wa' => $data->tutor->no_wa,
                    'email' => $data->tutor->user->email,
                    'status_tutor' => ($data->tutor->status == 'A') ? 'Aktif' : 'Nonaktif',
                ];
            });

        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil mengambil data courses...',
            'data' => $data,
        ], 200);
    }

    public function get_tutor()
    {
        $data = Tutor::whereHas('user', function($q) {
            $q->where('status', 'A');
        })->where('status', 'A')->get()
            ->map(function ($data) {
                return [
                    'tutor_user_id' => $data->user->id,
                    'nama_tutor' => $data->nama_tutor,
                ];
            });

        if ($data) {
            return response()->json([
                'status' => 'success',
                'message' => 'Data tutor...',
                'data' => $data
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tutor belum ada!',
                'data' => []
            ], 404);
        }
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tutor_user_id' => 'required',
            'nama_kursus' => 'required|max:100',
            'harga' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            $dataTutor = Tutor::where('user_id', $request->tutor_user_id)->first();

            $dataCourse = new Course();
            $dataCourse->tutor_id = $dataTutor->id;
            $dataCourse->nama_kursus = $request->nama_kursus;
            $dataCourse->harga = $request->harga;
            $dataCourse->status = 'A';
            $dataCourse->save();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil menambahkan data course!'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menambahkan data course!',
                'data' => $e->getMessage(),
            ], 500);
        }
    }

    public function edit($id)
    {
        try {
            $id = Crypt::decryptString($id);
            $data = Course::findOrFail($id);

            return response()->json([
                "status" => "success",
                "message" => "Data ditemukan!",
                "data" => [
                    "id" => Crypt::encryptString($data->id),
                    "tutor_user_id" => $data->tutor->user->id,
                    "nama_kursus" => $data->nama_kursus,
                    "harga" => $data->harga,
                    "status" => $data->status,
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
            'tutor_user_id' => 'required',
            'nama_kursus' => 'required|max:100',
            'harga' => 'required|numeric|min:0',
            'status' => 'required|max:1'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            $id = Crypt::decryptString($request->id);
            $dataTutor = Tutor::where('user_id', $request->tutor_user_id)->first();

            $dataCourse = Course::findOrFail($id);
            $dataCourse->tutor_id = $dataTutor->id;
            $dataCourse->nama_kursus = $request->nama_kursus;
            $dataCourse->harga = $request->harga;
            $dataCourse->status = $request->status;
            $dataCourse->update();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil mengubah data course!'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengubah data course!',
                'data' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $id = Crypt::decryptString($id);
            $dataCourse = Course::findOrFail($id);

            if ($dataCourse->delete()) {
                DB::commit();
                return response()->json([
                    'status' => 'success',
                    'message' => 'Berhasil menghapus data course!'
                ], 200);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal menghapus data course!'
                ], 500);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus data course!',
                'data' => $e->getMessage(),
            ], 500);
        }
    }
}
