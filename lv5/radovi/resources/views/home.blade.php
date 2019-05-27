@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>
                @if (!Auth::guest())
                    @foreach($dataUsers as $user)
                        @if(Auth::user()->email == $user->email)
                            <div class="panel-body">
                                <p>@lang('messages.welcome1'){{ $user->name }}@lang('messages.welcome2')</p>
                            </div>
                        @endif
                    @endforeach
                @endif
            </div>
        </div>
    </div>
    @if(!Auth::guest())
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    @if(Auth::user()->role == 'Admin')
                        <div class="panel-heading">
                            <p>{{ Auth::user()->name }}@lang('messages.admin_message')</p>
                        </div>
                        <div class="panel-body">
                            @foreach($dataUsers as $user)
                                @if($user->role != 'Admin')
                                    <div class="row">
                                        <div class="col-md-3">
                                            <h4>{{ $user->name }}</h4>
                                        </div>
                                        <div class="col-md-3">
                                            <h4>{{ $user->role }}</h4>
                                        </div>
                                        <div class="col-md-6">
                                            <form method="post" action="editUser">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <input type="hidden" name="user_id" value="{{$user->id}}">
                                                <div class="col-md-3">
                                                    <select required class="selectpicker" name="role">
                                                        <option value="Profesor">@lang('messages.profesor')</option>
                                                        <option value="Student">@lang('messages.student')</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <button type="submit" class="btn btn-info">@lang('messages.button')</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @elseif(Auth::user()->role == 'Profesor')
                        <div class="panel-heading">
                            <p>{{ Auth::user()->name }}@lang('messages.prof_message')</p>
                        </div>
                        <div class="panel-body">
                            <label for="hrvatski" class="col-md-4 control-label">@lang('messages.menu_lang_cro')</label>
                            <form method="post" action="croatian" name="hrvatski">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="user_id" value="{{Auth::user()->id}}">
                                <input type="hidden" name="locale" value="hr">
                                <button type="submit">Hr</button>
                            </form>
                            <label for="engleski" class="col-md-4 control-label">@lang('messages.menu_lang_eng')</label>
                            <form method="post" action="english" name="engleski">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="user_id" value="{{Auth::user()->id}}">
                                <input type="hidden" name="locale" value="en">
                                <button type="submit">En</button>
                            </form>
                            <a href="{{ url('/dodajRad') }}">@lang('messages.add_task')</a>
                        </div>
                        <div class="panel-body">
                            <table class="table">
                                <thead>
                                  <tr>
                                    <th>@lang('messages.name')</th>
                                    <th>@lang('messages.name_english')</th>
                                    <th>@lang('messages.description')</th>
                                    <th>@lang('messages.study_type')</th>
                                    <th>@lang('messages.student')</th>
                                  </tr>
                                </thead>
                                <tbody>
                                @foreach($dataTasks as $task)
                                    @if($task->profesor==Auth::user()->name)
                                      <tr>
                                        <td>{{ $task->naziv_rada }}</td>
                                        <td>{{ $task->naziv_na_engleskom }}</td>
                                        <td>{{ $task->zadatak_rada }}</td>
                                        <td>{{ $task->tip_studija }}</td>
                                        <td>
                                            @if($task->odabrani_student == null)
                                            <form method="get" action="prihvacanje">
                                                <input type="hidden" name="taskId" value="{{ $task->id }}">
                                                <button type="submit" class="btn btn-info">Odaberi studenta</button>
                                            </form>
                                            @else
                                                <label>Odabrani student:</label>
                                                <p>{{$task->odabrani_student}}</p>
                                            @endif
                                        </td>
                                      </tr>
                                    @endif
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="panel-heading">
                            <p>{{ Auth::user()->name }}@lang('messages.stud_message')</p>
                        </div>
                        <div class="panel-body">
                            <table class="table">
                                <thead>
                                  <tr>
                                    <th>@lang('messages.name')</th>
                                    <th>@lang('messages.name_english')</th>
                                    <th>@lang('messages.description')</th>
                                    <th>@lang('messages.study_type')</th>
                                    <th>@lang('messages.profesor')</th>
                                  </tr>
                                </thead>
                                <tbody>
                                @foreach($dataTasks as $task)
                                  <tr>
                                    <td>{{ $task->naziv_rada }}</td>
                                    <td>{{ $task->naziv_na_engleskom }}</td>
                                    <td>{{ $task->zadatak_rada }}</td>
                                    <td>{{ $task->tip_studija }}</td>
                                    <td>{{ $task->profesor }}</td>
                                    <td>
                                        <form method="post" action="prijava">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <input type="hidden" name="user" value="{{Auth::user()->name}}">
                                            <input type="hidden" name="taskId" value="{{$task->id}}">
                                            <button type="submit" class="btn btn-info" >@lang('messages.apply')</button>
                                        </form>
                                    </td>
                                  </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                    </div>
            </div>
        </div>
    @endif
</div>
@endsection