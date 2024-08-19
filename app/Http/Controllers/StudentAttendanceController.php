<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController;
use App\Models\Attendance;
use App\Models\Kelas;
use App\Models\Mahasiswa;
use App\Models\MataPelajaran;
use App\Models\StudentAttendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentAttendanceController extends BaseController
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function add(Request $request)
    {
        try {
            $pertemuan = new Attendance();
            $pertemuan->id_mapel = $request->id_mapel;
            $pertemuan->pertemuan = $request->pertemuan;
            $pertemuan->save();

            $mapel = MataPelajaran::findOrFail($request->id_mapel);
            $mahasiswa = Mahasiswa::where("id_class", $mapel->id_class)->get();

            foreach ($mahasiswa as $key => $value) {
                $absenStudent = new StudentAttendance();
                $absenStudent->id_pertemuan = $pertemuan->id;
                $absenStudent->id_mahasiswa = $value->id;
                $absenStudent->status = '-';
                $absenStudent->save();
            }

            return $this->sendResponse($pertemuan, 'tugas created successfully');
        } catch (\Throwable $th) {
            return $this->sendError('error creating tugas', $th->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $search = StudentAttendance::where("id_pertemuan", $request->id_pertemuan)->where("id_mahasiswa", $request->id_mahasiswa)->first();
            
            $absen = StudentAttendance::findOrFail($search->id);
            $absen->id_pertemuan = $request->id_pertemuan;
            $absen->id_mahasiswa = $request->id_mahasiswa;
            $absen->status = $request->status;
            $absen->save();

            return $this->sendResponse($search, 'absen created successfully');
        } catch (\Throwable $th) {
            return $this->sendError('error creating tugas', $th->getMessage());
        }
    }
    public function absenMandiri(Request $request)
    {
        try {
            $user = Auth::user();
            $mahasiswa = Mahasiswa::where("id_user", $user->id)->first();
            $search = StudentAttendance::where("id_pertemuan", $request->id_pertemuan)->where("id_mahasiswa", $mahasiswa->id)->first();

            $absen = StudentAttendance::findOrFail($search->id);
            $absen->id_pertemuan = $request->id_pertemuan;
            $absen->id_mahasiswa =$mahasiswa->id;
            $absen->status = $request->status;
            $absen->save();

            return $this->sendResponse($search, 'absen created successfully');
        } catch (\Throwable $th) {
            return $this->sendError('error creating tugas', $th->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $mapel = MataPelajaran::findOrFail($id);
            $mahasiswa = Mahasiswa::where("id_class", $mapel->id_class)->get();
            $pertemuan = Attendance::where("id_mapel", $id);

            foreach ($mahasiswa as $key => $value) {
                $value->absen = StudentAttendance::where("id_mahasiswa", $value->id)
                ->join('attendances', 'student_attendances.id_pertemuan', '=', 'attendances.id')->where("id_mapel",$id)->get();
            }
            return $this->sendResponse($mahasiswa, "mapel retrieved successfully");
        } catch (\Throwable $th) {
            return $this->sendError("error retrieving mapel", $th->getMessage());
        }
    }
}
