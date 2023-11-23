<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\BaseModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;
use Exception;
use Throwable;

class BaseController extends Controller
{
    protected $user_id;
    // construct
    public function __construct() {

        // agar user data bisa dibaca di _construct
        $this->middleware(function($request, $next){

            // Authorize menu access
            if ($request->hasHeader('Ports-Access-Control-Request-Type')) {
                if($request->header('Ports-Access-Control-Request-Type') != 'api') {
                    $response = [
                        'status'=> false,
                        'message'=> 'Invalid header!'
                    ];
                    return response()->json($response)->setStatusCode(200);
                }
            }
            else {
                $menu_access = $this->menuAccess();
                if($menu_access['status'] == false) {
                    return abort('403', 'Unauthorized Access');
                }
                else {
                    View::share('url_segment', $menu_access['url_segment']);
                    View::share('url_parent', $menu_access['url_parent']);
                }
            }

            // get user id
            $this->user_id = Auth::user()->user_id;

            // get data menu system
            $utama =  $this->getMenuUtama($this->user_id);
            View::share('rs_parent_menu_utama', $utama['rs_parent_menu_utama']);
            View::share('rs_child_menu_utama', $utama['rs_child_menu_utama']);

            // get data menu system
            $data =  $this->getMenuSystem($this->user_id);
            View::share('rs_parent_menu_system', $data['rs_parent_menu_system']);
            View::share('rs_child_menu_system', $data['rs_child_menu_system']);

            // get user role
            $user_role = BaseModel::getUserRole('');
            View::share('role_user', $user_role->role_name);

            // get user role id
            $role_id = BaseModel::getUserRoleId();
            View::share('role_id', $role_id);


            // app name
            View::share('app_name', ucwords(strtolower(str_replace('-',' ',config('app.name')))));

            // return
            return $next($request);
        });
    }

    /**
     * Authorize menu access
     */
    protected static function menuAccess() {
        // get url
        $two_segment = request()->segment(1).'/'.request()->segment(2);
        $three_segment = request()->segment(1).'/'.request()->segment(2).'/'.request()->segment(3);
        $four_segment = request()->segment(1).'/'.request()->segment(2).'/'.request()->segment(3).'/'.request()->segment(4);

        // cek database
        $menu2 = BaseModel::getMenuAccessByUrl($two_segment);

        // segment 2 admin/..
        if(empty($menu2) || $menu2 == NULL ){

            // cek segment 3 admin/../..
            $menu3 = BaseModel::getMenuAccessByUrl($three_segment);
            if(empty($menu3) || $menu3 == NULL ){

                // cek segmen 4 admin/../../..
                $menu4 = BaseModel::getMenuAccessByUrl($four_segment);

                if(empty($menu4) || $menu4 == NULL ){
                    $data = [
                        'status'=> false,
                        'url_segment'=> ''
                    ];
                    return $data;
                }
                else {
                    // cek if menu not active
                    if($menu4->menu_active == '1') {
                        $data = [
                            'status'=> true,
                            'url_segment'=> $menu4->menu_url,
                            'url_parent'=> BaseModel::getParentMenuUrl($menu4->parent_menu_id)
                        ];
                        return $data;
                    }
                    else {
                        return abort(503);
                    }
                }

            }
            else {
                // cek if menu not active
                if($menu3->menu_active == '1') {
                    $data = [
                        'status'=> true,
                        'url_segment'=> $menu3->menu_url,
                        'url_parent'=> BaseModel::getParentMenuUrl($menu3->parent_menu_id)
                    ];
                    return $data;
                }
                else {
                    return abort(503);
                }
            }
        }
        else {
            // cek if menu not active
            if($menu2->menu_active == '1') {
                $data = [
                    'status'=> true,
                    'url_segment'=> $menu2->menu_url,
                    'url_parent'=> BaseModel::getParentMenuUrl($menu2->parent_menu_id)
                ];
                return $data;
            }
            else {
                return abort(503);
            }
        }
    }


    // get menu utama
    public function getMenuUtama($user_id) {
        // sub menu
        $rs_sub_menu = [];
        // parent menu
        $rs_parent_menu = BaseModel::parentMenuUtama($user_id);
        // looping
        foreach($rs_parent_menu as $menu) {
            $sub_menu = BaseModel::childMenuUtama($menu->menu_id, $user_id);
            if(count($sub_menu) != 0) {
                $rs_sub_menu[$menu->menu_id] = $sub_menu;
            }
        }
        // result
        $data = ['rs_parent_menu_utama' => $rs_parent_menu, 'rs_child_menu_utama'=> $rs_sub_menu];

        // return
        return $data;
    }

    // get menu system
    public function getMenuSystem($user_id) {
        // sub menu
        $rs_sub_menu = [];
        // parent menu
        $rs_parent_menu = BaseModel::parentMenuSystem($user_id);
        // looping
        foreach($rs_parent_menu as $menu) {
            $sub_menu = BaseModel::childMenuSystem($menu->menu_id, $user_id);
            if(count($sub_menu) != 0) {
               $rs_sub_menu[$menu->menu_id] = $sub_menu;
            }
        }
        // result
        $data = ['rs_parent_menu_system' => $rs_parent_menu, 'rs_child_menu_system'=> $rs_sub_menu];

        // return
        return $data;
    }


    /**
     * REDUCE IMAGE QUALITY
     */
    public function reduceImageQuality($imageSource, $imageMime, $quality) {
        if($imageMime == 'image/jpeg') {
            $image = imagecreatefromjpeg($imageSource);
        }
        else {
            $image = imagecreatefrompng($imageSource);
        }

        return imagejpeg($image, $imageSource, $quality);
    }


     /**
     * SEND MAIL
     */

    public function sendMail($data) {
        try {
            // try send mail
            Mail::to($data['user_email'])->send(new SendMail($data));

            return true;
        } catch (Throwable $e) {
            report($e);

            return false;
        }

    }


}
