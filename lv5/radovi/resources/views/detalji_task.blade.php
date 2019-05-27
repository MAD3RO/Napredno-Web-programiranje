@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="row panel-body">
                    <b>Detalji:</b>                            
                </div>
                @foreach($tasksData as $task)
                    <form method="post" action="prihvacanje/potvrda">
                        <input type="hidden" name="task_id" value="{{$task->id}}">
                        <div class="panel-body">
                            <table class="table">
                                <thead>
                                  <tr>
                                    <th>@lang('messages.name')</th>
                                    <th>@lang('messages.description')</th>
                                    <th>@lang('messages.study_type')</th>
                                    <th>@lang('messages.student')</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <tr>
                                    <td>{{$task->naziv_rada}}</td>
                                    <td>{{$task->zadatak_rada}}</td>
                                    <td>{{$task->tip_studija}}</td>
                                    <td>
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <select name="student">
                                            <optgroup>Odaberi jednog od prijavljenih studenata:</optgroup>
                                            @foreach($students as $student)
                                                <option>{{$student}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                  </tr>
                                </tbody>
                            </table>
                            <!-- <div class="row">
                                <label>Naziv:</label>
                                {{$task->naziv_rada}}
                            </div>
                            <div class="row">
                                <label>Opis:</label>
                                {{$task->zadatak_rada}}
                            </div>
                            <div class="row">
                                <label>Tip:</label>
                                {{$task->tip_studija}}
                            </div>
                            <div class="row">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <label>Prijavljeni studenti:</label>
                                <select name="student">
                                    <optgroup>Odaberi jednog od prijavljenih studenata:</optgroup>
                                    @foreach($students as $student)
                                        <option>{{$student}}</option>
                                    @endforeach
                                </select>
                            </div> -->                               
                        </div>
                        <button type="submit" class="btn btn-info">Confirm this student</button>
                     </form>
                 @endforeach
            </div>
        </div>
    </div>
</div>
@endsection