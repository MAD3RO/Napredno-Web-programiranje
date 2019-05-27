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

class TaskController extends Controller
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

    public function otvoriIzbornik(){
        $loggedUser = DB::table('users')->where('email', Auth::user()->email)->first();
        App::setlocale($loggedUser->locale);
        return view('add_task');
    }


    public function dodajRad(){
        $naziv_rada = Input::get('naziv_rada');
        $naziv_rada_eng = Input::get('naziv_rada_eng');
        $zadatak_rada = Input::get('zadatak_rada');
        $tip_stud = Input::get('tip_stud');
        $profesor = Input::get('profesor'); 
        DB::table('tasks')->insert(
            [
                'naziv_rada' => $naziv_rada, 
                'naziv_na_engleskom' => $naziv_rada_eng,
                'zadatak_rada' => $zadatak_rada, 
                'tip_studija' => $tip_stud, 
                'profesor' => $profesor
            ]
        );

        return redirect('/home');
    }

    public function prihvatiStudenta(){
        $task_id = Input::get('taskId');
        $tasksData = DB::table('tasks')->get()->where('id', $task_id);

        foreach ($tasksData as $task) {
            $students = array();
            $appliedStudents = array();
            array_push($appliedStudents,$task->studenti);
            $appliedStudentsParts = explode(',', $appliedStudents[0]);

            foreach($appliedStudentsParts as $part){
                array_push($students, $part);
            }
        }

        return view('detalji_task', ['tasksData' => $tasksData, 'students' => $students]);
    }
}