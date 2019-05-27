<?php
/**
 * Created by PhpStorm.
 * User: Josip
 * Date: 18.03.2017.
 * Time: 19:24
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CreateProjectController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = DB::table('users')->pluck('email');

        return view('create_project', ['users' => $users]);
    }
}