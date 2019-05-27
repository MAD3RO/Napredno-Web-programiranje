<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

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
        $projects = DB::table('projects')->get();
        $userEmail = Auth::user()->email;
        $data = array();

        foreach($projects as $project){

            if($project->done == "FALSE"){
                if($project->leader == $userEmail){

                    $project->admin = true;
                    array_push($data, $project);
                }
                else{

                    $associatesArray = array();
                    array_push($associatesArray, $project->associates );
                    $associatesArrayPieces = explode(',', $associatesArray[0]);

                    foreach($associatesArrayPieces as $arrayPiece){
                        if($arrayPiece == $userEmail){
                            $project->admin = false;
                            array_push($data, $project);
                        }
                    }//End of foreach

                }//end of else
            }

        }//End of foreach

        return view('home', ['data' => $data] );
    }

    //POST
    public function createNewProject(){
        $projectName = Input::get('projectName');
        $projectDescription = Input::get('projectDescription');
        $projectPrice = Input::get('projectPrice');
        $projectStartDate = Input::get('projectStartDate');
        $projectEndDate = Input::get('projectEndDate');
        $ProjectAssociates = Input::get('projectAssociates');
        $projectLeader = Input::get('projectLeader');

        $ProjectAssociates = implode(',', $ProjectAssociates);

        DB::table('projects')->insert(
            [
                'name' => $projectName, 'description' => $projectDescription,
                'price' => $projectPrice, 'startDate' => $projectStartDate,
                'endDate' => $projectEndDate, 'associates' => $ProjectAssociates,
                'done' => 'FALSE', 'leader' => $projectLeader
            ]
        );

        //Add to DB
        return redirect('home/');
    }

    public function finishProject(){
        $projectId = Input::get('projectId');
        DB::table('projects')
            ->where('id', $projectId)
            ->update(['done' => "TRUE"]);

        return redirect('home/');
    }

    public function editProject(){
        $projectId = Input::get('projectId');
        $project = DB::table('projects')->where('id', $projectId)->first();

        $users = DB::table('users')->pluck('email');

        //associates
        $associatesArray = array();
        array_push($associatesArray, $project->associates );
        $associatesArrayPieces = explode(',', $associatesArray[0]);

        //start date
        $startDateArray = array();
        array_push($startDateArray, $project->startDate );
        $startDateArrayPieces = explode(' ', $startDateArray[0]);
        $project->startDate = $startDateArrayPieces[0];

        //end date
        $endDateArray = array();
        array_push($endDateArray, $project->startDate );
        $endDateArrayPieces = explode(' ', $endDateArray[0]);
        $project->endDate = $endDateArrayPieces[0];

        $formattedUsers['rest'] = array();

        $fHas = 0;
       foreach($users as $user){
          foreach($associatesArrayPieces as $associate){
              if($user == $associate || $user == $project->leader)
                  $fHas = 1;
          }
           if($fHas != 1)
               array_push($formattedUsers['rest'], $user);
           else
               $fHas = 0;
       }

        $formattedUsers['associates'] = $associatesArrayPieces;
        $formattedUsers['leader'] = $project->leader;

        return view('edit_project', ['data' => $project, 'projectId' => $projectId, 'users' => $formattedUsers] );
    }

    public function editProjectOk() {
        $projectName = Input::get('projectName');
        $projectDescription = Input::get('projectDescription');
        $projectPrice = Input::get('projectPrice');
        $projectStartDate = Input::get('projectStartDate');
        $projectEndDate = Input::get('projectEndDate');
        $ProjectAssociates = Input::get('projectAssociates');
        $projectLeader = Input::get('projectLeader');
        $projectId = Input::get('projectId');

        $ProjectAssociates = implode(',', $ProjectAssociates);

        DB::table('projects')->where('id', $projectId)->update(
            [
                'name' => $projectName, 'description' => $projectDescription,
                'price' => $projectPrice, 'startDate' => $projectStartDate,
                'endDate' => $projectEndDate, 'associates' => $ProjectAssociates,
                'done' => 'FALSE', 'leader' => $projectLeader
            ]
        );

        //Add to DB
        return redirect('home/');
    }

    function deleteProject(){
        $projectId = Input::get('projectId');
        $location = Input::get('location');
        DB::table('projects')->where('id', $projectId)->delete();

        return redirect($location.'/');
    }

    function finishedProjects(){

        $projects = DB::table('projects')->get();
        $userEmail = Auth::user()->email;
        $data = array();

        foreach($projects as $project){

            if($project->done == "TRUE"){

                if( $project->leader == $userEmail ){

                    $project->admin = true;
                    array_push($data, $project);
                }
                else{

                    $associatesArray = array();
                    array_push($associatesArray, $project->associates );
                    $associatesArrayPieces = explode(',', $associatesArray[0]);

                    foreach($associatesArrayPieces as $arrayPiece){
                        if($arrayPiece == $userEmail){
                            $project->admin = false;
                            array_push($data, $project);
                        }
                    }//End of foreach

                }//end of else

            }

        }//End of foreach

        return view('finished', ['data' => $data] );

    }

    function profile(){
        $id = Auth::id();
        $user['basics'] = DB::table('users')->where('id', $id)->first();
        $user['leader'] = DB::table('projects')->where('leader', $user['basics']->email)->get();
        $user['leader_count'] = count($user['leader']);
        $projects = DB::table('projects')->get();
        $user['associate'] = array();


        $userEmail = $user['basics']->email;

        foreach($projects as $project){
            $associatesArray = array();
            array_push($associatesArray, $project->associates );
            $associatesArrayPieces = explode(',', $associatesArray[0]);

            foreach($associatesArrayPieces as $arrayPiece){
                if($arrayPiece == $userEmail){
                    $project->admin = false;
                    array_push($user['associate'], $project);
                }
            }//End of foreach
        }//End of foreach

        $user['associate_count'] = count($user['associate']);

        return view('profile', ['data' => $user] );
    }

}
