@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-body h2">
                   Edit Project: {{$data->name}}
                </div>
                <div class="panel-body">
                    <form method="post" action="editProject">
                        <div class="form-group">
                            <label for="labelProjectName">Project name</label>
                            <input type="text" class="form-control" name="projectName" id="projectName" placeholder="Enter name of the project" required value="{{$data->name}}">
                        </div>
                        <div class="form-group">
                            <label for="labelProjectDescription">Project description</label>
                            <textarea class="form-control" name="projectDescription" id="projectDescription" rows="3" required >{{$data->description}}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="labelProjectPrice">Project price [USD]</label>
                            <input type="number" class="form-control" name="projectPrice" id="projectPrice" placeholder="Price of the project" required value="{{$data->price}}">
                        </div>
                        <div class="form-group">
                            <label for="labelProjectStartDate">Start date</label>
                            <input type="date" class="form-control" name="projectStartDate" id="projectStartDate" required value="{{$data->startDate}}">
                        </div>

                        <div class="form-group">
                            <label for="labelProjectEndDate">End date</label>
                            <input type="date" class="form-control" name="projectEndDate" id="projectEndDate" required value="{{$data->endDate}}">
                        </div>

                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="projectId" value="{{ $projectId }}">
                        <input type="hidden" name="projectLeader" value="{{ Auth::user()->email }}">

                        <div class="form-group">
                            <label for="LabelProjectAssociates">Associates</label>
                            <select class="selectpicker" multiple data-actions-box="true" name="projectAssociates[]" required>
                                @foreach($users['associates'] as $user)
                                    <option selected>{{ $user }}</option>
                                @endforeach
                                    @foreach($users['rest'] as $user)
                                        <option>{{ $user }}</option>
                                    @endforeach
                                <option disabled>{{$users['leader']}} - Project leader</option>
                            </select>

                        </div>

                        <button type="submit" class="btn btn-primary">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
