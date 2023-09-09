@extends('admin.templates.index')

@section('page-title')
    Profile
@stop

@section('title')
    Profile
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form method="post" name="setting-form" id="setting-form" action="{{url('admin/profile')}}">
                @csrf
                <input type="hidden" name="id" value="{{auth()->id()}}">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label required" for="name">Name</label>
                            <input type="text" class="form-control" value="{{auth()->user()->name}}"
                                   name="name" id="name">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label required" for="email">Email</label>
                            <input type="email" class="form-control" value="{{auth()->user()->email}}"
                                   name="email" id="email">
                        </div>
                    </div>
                </div>
                <div>
                    <button class="btn btn-primary" type="submit">Save</button>
                </div>
            </form>
        </div>
    </div>
@stop

@section('scripts')

@stop
