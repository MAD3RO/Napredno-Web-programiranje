@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-body h2">
                   Projects
                </div>
                @foreach($data as $item)
                    <div class="panel panel-primary">

                        <div class="panel-heading">{{$item->name}}</div>
                        <div class="panel-body">Description: {{$item->description}}</div>
                        <div class="panel-body">Price: {{$item->price}} USD</div>
                        <div class="panel-body">Start date: {{$item->startDate}}</div>
                        <div class="panel-body">End date: {{$item->endDate}}</div>
                        <div class="panel-body">Leader: {{$item->leader}}</div>
                        <div class="panel-body">Associates: {{$item->associates}}</div>

                        <div class="panel-body">Done: {{$item->done}}
                            @if($item->done == "FALSE")
                                <form method="post" action="finish">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="projectId" value="{{ $item->id }}">
                                    <button type="submit" name="button" class="btn btn-primary btn-sm" >Finish</button>
                                </form>
                            @endif
                        </div>
                        @if($item->admin == true && $item->done == "FALSE")
                            <form method="post" action="edit">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="projectId" value="{{ $item->id }}">
                                <button type="submit" class="btn btn-red btn-lg btn-block" name="edit">Edit</button>
                            </form>
                        @endif
                        @if($item->admin == true)
                            <br>
                            <form method="post" action="delete">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="projectId" value="{{ $item->id }}">
                                <input type="hidden" name="location" value="home">
                                <button type="submit" class="btn btn-red btn-lg btn-block" name="delete">Delete</button>
                            </form>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
