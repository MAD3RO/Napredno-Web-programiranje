@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Select role</div>
                @if (!Auth::guest())
                    <div class="panel-body">
                        <form method="post" action="home">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <select required class="selectpicker" name="role">
                                <option value="Profesor">Profesor</option>
                                <option value="Student">Student</option>
                            </select>
                            <br>
                            <button type="submit" class="btn btn-info">Proceed</button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection