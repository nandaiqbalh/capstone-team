<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\Superadmin\Settings\Accounts;


class UsersImport implements ToModel, WithHeadingRow
{
    protected $failedRows;

    public function __construct(&$failedRows)
    {
        $this->failedRows = &$failedRows;
    }

    public function model(array $row)
    {
        // Validate the nomor_induk field for uniqueness against existing database records
        $validator = Validator::make($row, [
            'nomor_induk' => 'required|unique:app_user,nomor_induk', // Assuming 'nomor_induk' is the column name for nomor_induk in database table
            'role_id' => 'required',
            'user_name' => 'required',
            'user_password' => 'required',
            'angkatan' => 'required',
            'jenis_kelamin' => 'required',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            // Collect usernames of failed rows
            $this->failedRows[] = $row['user_name'];

            // Handle validation errors (you can log errors or skip the row)
            return null; // Returning null will skip saving this row
        }

        // Check if nomor_induk already exists in the database
        $existingUser = User::where('nomor_induk', $row['nomor_induk'])->first();
        if ($existingUser) {
            // If nomor_induk already exists, skip saving this row
            return null;
        }

        // Generate user_id using microtime
        $user_id = Accounts::makeMicrotimeID();

        // Create a new User instance
        $user = new User([
            'user_id' => $user_id,
            'role_id' => $row['role_id'],
            'user_name' => $row['user_name'],
            'user_password' => Hash::make($row['user_password']),
            'nomor_induk' => $row['nomor_induk'],
            'angkatan' => $row['angkatan'],
            'jenis_kelamin' => $row['jenis_kelamin'],
            'created_by' => Auth::user()->user_id,
            'created_date' => now()
        ]);

        // Disable timestamps for this model to avoid automatic timestamp updates
        $user->timestamps = false;

        return $user;
    }
}
