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
            <form method="post" name="setting-form" id="setting-form" enctype="multipart/form-data" action="{{url('admin/profile')}}">
                @csrf
                <input type="hidden" name="id" value="{{auth()->id()}}">
                <div class="d-flex justify-content-end">
                    <div class="col-md-4 col-sm-4 text-center mt-1">
                        @php
                            $url = auth()->user()->photo ? auth()->user()->photo : asset('assets/site/img/user.png')
                        @endphp
                        <img src="{{$url}}" width="130px" height="130px" id="profile-photo"
                             style="object-fit: cover;object-position: top;border-radius: 50%;"
                             class="border border-muted p-1 shadow">
                    </div>
                </div>
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
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label" for="photo">Profile Photo</label>
                            <input type="file" class="form-control" name="photo" id="photo" onchange="setPhoto(this)">
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
    <script>
        function setPhoto(ins) {
            const [file] = ins.files
            if (file) {
                var output = document.getElementById('profile-photo');
                output.src = URL.createObjectURL(file);
                output.onload = function() {
                    URL.revokeObjectURL(output.src) // free memory
                }
            }
        }
    </script>
@stop
