<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Hash;
use App\Models\Superadmin\Settings\Accounts;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Menghasilkan user_id menggunakan microtime
        $user_id = Accounts::makeMicrotimeID();

        $user = new User([
            'user_id' => $user_id,
            'role_id' => $row['role'],
            'user_name' => $row['nama'],
            'user_email' => $row['email'],
            'user_password' => Hash::make($row['password']),
            'user_active' => $row['active'],
            'nomor_induk' => $row['nim'],
            'no_telp' => $row['telepon'],
            'angkatan' => $row['angkatan'],
            'jenis_kelamin' => $row['kelamin'],
            'created_by' => Auth::user()->user_id,
            'created_date' => now()
        ]);

        // Mengatur opsi timestamps menjadi false
        $user->timestamps = false;

        return $user;
    }


}
