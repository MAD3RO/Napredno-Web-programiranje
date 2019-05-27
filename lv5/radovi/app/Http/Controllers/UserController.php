<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use App;
use Illuminate\Foundation\Application;

class UserController extends Controller
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
    public function setUserRole()
    {
        if (Auth::check()) {
            $userId = Auth::user()->id;
            $userRole = Input::get('role');
            DB::table('users')->where('id', $userId)->update(['role' => $userRole]);

            return view('welcome');
        }
    }

    public function editUserRole(){

        $userId = Input::get('user_id');
        $role = Input::get('role');
        DB::table('users')->where('id', $userId)->update(['role' => $role]);
        
        return Redirect::to('/home');
    }

    public function prijaviSe(){
        $user = Input::get('user');
        $taskId = Input::get('taskId');
        $studentslist = DB::table('tasks')->get()->where('id',$taskId)->pluck('studenti');
        
        $studentslistString = (string)$studentslist[0];
        if (Auth::user()->role == 'Student' && strpos($studentslistString, $user) !== true) {
            if($studentslistString == "")
                $studentslistString = $user;
            else{
                $studentslistString = $studentslistString . ", " . $user;
            }
        }

        DB::table('tasks')->where('id', $taskId)->update(['studenti' => $studentslistString]);

        return Redirect::to('/home');
    }

    public function potvrdiStudenta(){
        $student = Input::get('student');
        $taskId = Input::get('task_id');

        $studentsInTask = DB::table('tasks')->get()->where('id',$taskId);

        DB::table('tasks')->where('id', $taskId)->update(['odabrani_student' => $student]);

        return Redirect::to('/home'); 
    }
}