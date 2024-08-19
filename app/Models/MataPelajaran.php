<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class MataPelajaran extends Authenticatable implements JWTSubject
{
    use HasFactory;
    protected $table = 'mata_pelajarans';
    protected $fillable = [
        'id',
        'id_class',
        'id_dosen',
        'nama_mapel',
        'deskripsi_mapel',
        'room',
        'sks',
        'day',
        'start_time',
        'end_time',
        'created_at',
        'updated_at',
    ];

    protected $guarded = [];

    protected $primaryKey = 'id';
    public function dosens()
    {
        return $this->belongsTo(Dosen::class);
    }
    public function materi()
    {
        return $this->hasMany(materi::class, 'id_mapel');
    }
    public function tugas()
    {
        return $this->hasMany(tugas::class, 'id_mapel');
    }
    public function attendance()
    {
        return $this->hasMany(Attendance::class, 'id_mapel');
    }

    public function thread()
    {
        return $this->hasMany(Thread::class, 'id_mapel');
    }
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
