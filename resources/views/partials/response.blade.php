@if($errors->any())
    var err = '';
    @foreach ($errors->all() as $error)
        err += `{{$error}} \n`;
    @endforeach
    toast(err, 'error');
@endif

@if(session()->has('error'))
    toast('{{session()->get('error')}}', 'error');
@endif

@if(session()->has('success'))
    toast('{{session()->get('success')}}', 'success');
@endif

@if(session()->has('warning'))
    toast('{{session()->get('warning')}}', 'warning');
@endif

@if(session()->has('info'))
    toast('{{session()->get('info')}}', 'warning');
@endif
