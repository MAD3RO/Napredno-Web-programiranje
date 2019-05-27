@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-body h2">
                   Create Project
                </div>
                <div class="panel-body">
                    <form method="post" action="createNew">
                        <div class="form-group">
                            <label for="labelProjectName">Project name</label>
                            <input type="text" class="form-control" name="projectName" id="projectName" placeholder="Enter name of the project" required>
                        </div>
                        <div class="form-group">
                            <label for="labelProjectDescription">Project description</label>
                            <textarea class="form-control" name="projectDescription" id="projectDescription" rows="3" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="labelProjectPrice">Project price [USD]</label>
                            <input type="number" class="form-control" name="projectPrice" id="projectPrice" placeholder="Price of the project" required>
                        </div>
                        <div class="form-group">
                            <label for="labelProjectStartDate">Start date</label>
                            <input type="date" class="form-control" name="projectStartDate" id="projectStartDate" required>
                        </div>
                        <div class="form-group">
                            <label for="labelProjectEndDate">End date</label>
                            <input type="date" class="form-control" name="projectEndDate" id="projectEndDate" required>
                        </div>

                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="projectLeader" value="{{ Auth::user()->email }}">

                        <div class="form-group">
                            <label for="LabelProjectAssociates">Associates</label>
                            <select class="selectpicker" multiple data-actions-box="true" name="projectAssociates[]" required>
                                @foreach($users as $user)
                                    @if($user == Auth::user()->email)
                                        <option disabled>{{ $user }} - Project Leader</option>
                                    @else
                                   <option>{{ $user }}</option>
                                    @endif
                                @endforeach
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
