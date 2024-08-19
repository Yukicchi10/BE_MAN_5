<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class MataPelajaranResource extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'idMapel' => $this->idMapel,
            'nama_mapel' => $this->nama_mapel,
            'deskripsi_mapel' => $this->deskripsi_mapel,

            /**
         *  jika suatu field memiliki relasi terhadap tabel lain maka gunakan kode ini
         *  misal tabel siswa memiliki hubungan dengan tabel nilai
         *  'nilai' => ($this->kode1) ? [
         *      $this->kode1->namafield,
         *      $this->kode1->namafield,
         *  ] : '',

         *  keterangan :
         *  - kode 1 : fungsi pada model yang mendefiniskan relasi dengan tabel lain yang dituju, misal tabel nilai
         *  ex:
         *  'nilai' => ($this->nilai) ? [
         *    $this->nilai->semester,
         *    $this->nilai->nilaiAngka,
         *  ] : '',
         */
        ];
    }
}
