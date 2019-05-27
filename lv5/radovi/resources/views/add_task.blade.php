@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">@lang('messages.add_task')</div>
                @if (!Auth::guest())
                    <div class="panel-body">
                        <form method="post" action="dodajRad">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="profesor" value="{{ Auth::user()->name }}">
                            <label for="naziv_rada" class="col-md-4 control-label">@lang('messages.name')</label>
                            <input type="text" class="form-control" name="naziv_rada" required>
                            <label for="naziv_rada_eng" class="col-md-4 control-label">@lang('messages.name_english')</label>
                            <input type="text" class="form-control" name="naziv_rada_eng" required>
                            <label for="zadatak_rada" class="col-md-4 control-label">@lang('messages.description')</label>
                            <input type="text" class="form-control" name="zadatak_rada" required>
                            <br>
                            <label for="tip_stud" class="col-md-4 control-label">@lang('messages.study_type')</label>
                            <div class="col-md-3">
                                <select required class="selectpicker" name="tip_stud">
                                    <option value="Diplomski">@lang('messages.graduate')</option>
                                    <option value="Preddiplomski">@lang('messages.undergraduate')</option>
                                    <option value="Strucni">@lang('messages.prof_stud_prog')</option>
                                </select>
                            </div>
                            <br>
                            <br>
                            <button type="submit" class="btn btn-primary btn-lg btn-block">@lang('messages.add_task')</button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection