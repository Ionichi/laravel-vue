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
        $data = Course::has('tutor')->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('nama_kursus', function ($q) {
                return ucwords($q->nama_kursus);
            })
            ->editColumn('harga', function ($q) {
                return "Rp " . number_format($q->harga);
            })
            ->editColumn('status', function ($q) {
                if ($q->status == 'A') {
                    return 'Aktif';
                } else {
                    return 'Nonaktif';
                }
            })
            ->addColumn('nama_tutor', function ($q) {
                return ucwords($q->tutor->nama_tutor);
            })
            ->addColumn('username', function ($q) {
                return $q->tutor->user->username;
            })
            ->addColumn('gender', function ($q) {
                if ($q->tutor->user->gender == 'L') {
                    return 'Laki-laki';
                } else {
                    return 'Perempuan';
                }
            })
            ->addColumn('no_wa', function ($q) {
                return ucwords($q->tutor->no_wa);
            })
            ->addColumn('email', function ($q) {
                return $q->tutor->user->email;
            })
            ->addColumn('role', function ($q) {
                if ($q->tutor->user->role == 'A') {
                    return 'Admin';
                } else if ($q->tutor->user->role == 'T') {
                    return 'Tutor';
                } else {
                    return 'Murid';
                }
            })
            ->addColumn('status_tutor', function ($q) {
                if ($q->tutor->status == 'A') {
                    return 'Aktif';
                } else {
                    return 'Nonaktif';
                }
            })
            ->addColumn('action', function ($q) {
                $button = '<button type="button" id="' . Crypt::encryptString($q->id) . '" class="btnEdit btn btn-warning waves-effect waves-light rounded mr-1"><i class="fa fa-edit"></i></button>';
                $button .= '<button type="button" id="' . Crypt::encryptString($q->id) . '" class="btnDelete btn btn-danger waves-effect waves-light rounded mr-1"><i class="fa fa-trash"></i></button>';
                return $button;
            })
            ->rawColumns(['action'])->make(true);
    }

    public function get_tutor()
    {
        $data = Tutor::has('user')->where('status', 'A')->get()
            ->map(function ($data) {
                return [
                    'tutor_id' => Crypt::encryptString($data->id),
                    'nama_tutor' => $data->nama_tutor,
                    'status' => $data->status,
                ];
            });

        if ($data) {
            return response()->json([
                'status' => 'success',
                'message' => 'Data tutor',
                'data' => $data
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tutor belum ada!',
                'data' => []
            ]);
        }
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tutor_id' => 'required',
            'nama_kursus' => 'required|max:100',
            'harga' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            $tutor_id = Crypt::decryptString($request->tutor_id);

            $dataCourse = new Course();
            $dataCourse->tutor_id = $tutor_id;
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
                    "tutor_id" => Crypt::encryptString($data->tutor_id),
                    "nama_kursus" => $data->nama_kursus,
                    "harga" => $data->harga,
                    "status" => $data->status,
                ]
            ]);
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
            'tutor_id' => 'required',
            'nama_kursus' => 'required|max:100',
            'harga' => 'required|numeric',
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
            $tutor_id = Crypt::decryptString($request->tutor_id);

            $dataCourse = Course::findOrFail($id);
            $dataCourse->tutor_id = $tutor_id;
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
