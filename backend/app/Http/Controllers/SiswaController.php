<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Yajra\DataTables\Facades\DataTables;

class SiswaController extends Controller
{
    public function index() {
        $siswa = Siswa::has('user')->get();
        return DataTables::of($siswa)
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
                return $q->user->fullname;
            })
            ->addColumn('gender', function ($q) {
                return $q->user->gender;
            })
            ->addColumn('email', function ($q) {
                return $q->user->email;
            })
            ->addColumn('role', function ($q) {
                return $q->user->role;
            })
            ->addColumn('status_user', function ($q) {
                return $q->user->status;
            })
            ->rawColumns([''])->make(true);
    }
}
