<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

/**
 * API VERSION 1
 */

use App\Http\Controllers\Api\V1\Auth\LoginController;
use App\Http\Controllers\Api\V1\Auth\LogoutController;
use App\Http\Controllers\Api\V1\Auth\ResetPasswordController;
use App\Http\Controllers\Api\V1\TriggerNode\TriggerNodeController;
use App\Http\Controllers\Api\V1\Revisi\RevisiController;


Route::post('/v1/auth/login/', [LoginController::class, 'authenticate']);
Route::post('/v1/auth/reset-password/', [ResetPasswordController::class, 'resetPasswordProcess']);
Route::get('/v1/assessment-trigger', [TriggerNodeController::class, 'Trigger']);
Route::get('/v1/assignment-trigger', [TriggerNodeController::class, 'TriggerAssignment']);


Route::middleware('auth:sanctum')->group(function () {
    // auth logoout
    Route::get('/v1/auth/logout', [LogoutController::class, 'logout']);

    // user profile
    Route::get('/v1/user/profile', function (Request $request) {
        $data = $request->user();
        // unset
        Arr::forget($data, 'created_by');
        Arr::forget($data, 'created_date');
        Arr::forget($data, 'modified_by');
        Arr::forget($data, 'modified_date');

        // user role
        $role = DB::table('app_role')
            ->join('app_role_user', 'app_role.role_id', '=', 'app_role_user.role_id')
            ->where('user_id', $request->user()->user_id)
            ->value('role_name');

        Arr::add($data, 'user_role', $role);

        // BranchName
        $branch_name = DB::table('master_branch')
            ->select('name as branch_name')
            ->where('id', $request->user()->branch_id)
            ->value('name');

        Arr::add($data, 'branch_name', $branch_name);

        // return json
        $response = [
            "status" => true,
            "message" => 'OK',
            "data" => $data
        ];

        return response()->json($response)->setStatusCode(200);
    });
    // Route::get('/v1/trigger', [TriggerNodeController::class,'Trigger']);
    // -------------------------------------------------------------------
    Route::post('/v1/revisi', [RevisiController::class, 'Revisi']);
    Route::post('/v1/revisi-pekerjaan', [RevisiController::class, 'RevisiPekerjaan']);
});
