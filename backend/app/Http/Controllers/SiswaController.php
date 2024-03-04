<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
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
            ->rawColumns([''])->make(true);
    }

    public function edit($id) {
        $data = Siswa::find($id);
        return response()->json([
            "id" => $data->id,
            "user_id" => $data->user_id,
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
            "role" => $data->user->role,
            "status_user" => $data->user->status,
        ]);
    }
}