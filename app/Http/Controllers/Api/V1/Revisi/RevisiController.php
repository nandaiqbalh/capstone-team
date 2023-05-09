<?php

namespace App\Http\Controllers\Api\V1\Revisi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;
use App\Models\Api\V1\Revisi\RevisiModel;

use DateTime;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;
use Throwable;

class RevisiController extends Controller
{

    /**
     * Triger Node JS.
     *
     * @return \Illuminate\Http\Response
     */
    public function Revisi(Request $request)
    {

        // cek Revisi
        $branch_id = RevisiModel::getBranchById($request->branch_id);
        $round_id = RevisiModel::getRound($request->round_id);

        // params mail
        $data = [
            'title'=> 'Revisi Penilaian',
            'nama_ronde'=> $round_id->name,
            'nama_rs'=> $branch_id->branch_name,
            'user_name' => $branch_id->user_name,
            'user_email' => $branch_id->user_email,
            'url' => env('APP_URL').'/admin/verifikator/ronde/penilaian',
            'email_type'=> 'revision-clear-round'
        ];
        // send mail
        if($this->sendMail($data)) {
            // flash message
            $response = [
                "status"=> True,
                "message"=> 'Email Terkirim!',
            ];
            return response()->json($response)->setStatusCode(200);
        }
        else {

            // response message
            $response = [
                "status"=> false,
                "message"=> 'Gagal mengirim E-mail, silahkan coba beberapa saat lagi!'
            ];
            return response()->json($response)->setStatusCode(200);
        }
    }

    public function RevisiPekerjaan(Request $request)
    {

        // cek Revisi
        $branch_id = RevisiModel::getBranchById($request->branch_id);
        
        // ronde pekerjaan
        (($request->round_id - 1) > 0) ? ($round_assignment = $request->round_id - 1) : $round_assignment = 4;
        $round_id = RevisiModel::getRound($round_assignment);

        // params mail
        $data = [
            'title' => 'Revisi Pekerjaan',
            'nama_ronde' => $round_id->name,
            'nama_rs' => $branch_id->branch_name,
            'user_name' => $branch_id->user_name,
            'user_email' => $branch_id->user_email,
            'url' => env('APP_URL') . '/admin/verifikator/ronde/pekerjaan',
            'email_type' => 'revision-clear-pekerjaan'
        ];
        // send mail
        if ($this->sendMail($data)) {
            // flash message
            $response = [
                "status" => True,
                "message" => 'Email Terkirim!',
            ];
            return response()->json($response)->setStatusCode(200);
        } else {

            // response message
            $response = [
                "status" => false,
                "message" => 'Gagal mengirim E-mail, silahkan coba beberapa saat lagi!'
            ];
            return response()->json($response)->setStatusCode(200);
        }
    }
    
    // sendMail
    public function sendMail($data) {
        try {
            // try send mail
            Mail::to($data['user_email'])->send(new SendMail($data));
            // dd('berhasil');
            return true;
        } catch (Throwable $e) {
            report($e);
            // dd($e);
            return false;
        }
    }
}