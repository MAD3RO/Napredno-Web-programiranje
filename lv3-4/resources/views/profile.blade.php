@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-body h2">
                   Profile
                </div>

                <div class="panel-body">
                    Name: {{$data['basics']->name}}
                </div>
                <div class="panel-body">
                    Email: {{$data['basics']->email}}
                </div>
                <div class="panel-body">
                    Registered at: {{$data['basics']->created_at}}
                </div>

                <div class="panel-body">
                    Leader on {{$data['leader_count']}} project/s

                    @foreach($data['leader'] as $project)
                        <div class="row">
                            <p class="col-md-5 col-md-offset-3">{{$project->name}} -
                                @if($project->done == "TRUE")
                                    DONE
                                @else
                                    NOT OVER
                                @endif
                            </p>
                        </div>
                    @endforeach
                </div>


                <div class="panel-body">
                    Associate on {{$data['associate_count']}} project/s

                    @foreach($data['associate'] as $project)
                        <div class="row">
                            <p class="col-md-5 col-md-offset-3">{{$project->name}} -
                                @if($project->done == "TRUE")
                                    DONE
                                @else
                                    NOT OVER
                                @endif
                            </p>
                        </div>
                    @endforeach
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
