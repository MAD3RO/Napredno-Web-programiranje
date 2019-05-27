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

class HomeController extends Controller
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
        //dohvacanje logiranog usera i trenutnog jezika
        $loggedUser = DB::table('users')->where('email', Auth::user()->email)->first();
        App::setlocale($loggedUser->locale);
        
        //dohvacanje svih usera
        $allUsers = DB::table('users')->get();
        $dataUsers = array();
        foreach ($allUsers as $user) {
            array_push($dataUsers, $user);
        }
        //dohvacanje svih task-ova
        $allTasks = DB::table('tasks')->get();
        $dataTasks = array();
        foreach ($allTasks as $task) {
            array_push($dataTasks, $task);
        }
        if (Auth::user()->role == '/') {
            return view('role_selection');
        }
        else{
            //prosljeđivanje home view-a uz polje sa svim korisnicima i polje sa svim task-ovima
            return view('home', ['dataUsers' => $dataUsers, 'dataTasks' => $dataTasks]);
        }
    }

    public function promijeniJezikNaEng()
    {

        $userId = Input::get('user_id');
        $locale = Input::get('locale');

        DB::table('users')->where('id', $userId)->update(['locale' => 'en']);
        return Redirect::to('/home');
    }

    public function promijeniJezikNaHr()
    {

        $userId = Input::get('user_id');
        $locale = Input::get('locale');

        DB::table('users')->where('id', $userId)->update(['locale' => 'hr']);
        return Redirect::to('/home');
    }
}
