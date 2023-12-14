<?php

namespace App\Models\TimCapstone\RuangSidang;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RuangSidang extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'kode_ruang',
        'nama_ruang',
    ];

    public function jadwalSidangProposals()
    {
        return $this->hasMany(JadwalSidangProposal::class, 'ruangan_id');
    }
}
