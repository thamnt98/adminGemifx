@extends('layouts.base')
@section('css')
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <link href="{{ asset('css/bootstrap-multiselect.min.css') }}" rel="stylesheet">
    <script src="{{ asset('js/bootstrap-multiselect.min.js') }}"></script>
    <link href="{{ asset('css/email.css') }}" rel="stylesheet">
@endsection
@section('content')
    <div class="container-fluid">
        @if ($message = Session::get('error'))
            <div class="alert alert-danger alert-block">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>{{ $message }}</strong>
            </div>
        @endif
        @if ($message = Session::get('success'))
            <div class="alert alert-success alert-block">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>{{ $message }}</strong>
            </div>
        @endif
        <form method="post" action="{{ route('email.marketing.send') }}">
            @csrf
            <div class="form-row">
                <div class="form-group col-md-6">
                    <a target="_blank" href="{{ url('/maileclipse/templates') }}"> <i class="fa fa-angle-double-right"></i>Edit Email Template</a>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Select Email Template</label>
                    <select class="form-control" name="template_email" required>
                        @foreach($templates as $template)
                            <option value="{{ $template->template_slug }}"> {{ $template->template_name }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('template_email'))
                        <span class="text-danger text-md-left">{{ $errors->first('template_email') }}</span>
                    @endif
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Subject</label>
                    <input class="form-control" type="text" name="title" value="{{ old('title') }}" required>
                    @if($errors->has('title'))
                        <span class="text-danger text-md-left">{{ $errors->first('title') }}</span>
                    @endif
                </div>
                <div class="form-group col-md-6">
                    <label>Users</label>
                    <div class="example">
                        <select id="example-enableCollapsibleOptGroups-enableClickableOptGroups-enableFiltering-includeSelectAllOption"  class="form-control" multiple="multiple" name="users[]">
                            <optgroup label="Agent Account">
                                @foreach($users['agents'] as $c1)
                                    <option value="{{ $c1->email }}">{{ $c1->email }}</option>
                                @endforeach
                            </optgroup>
                            <optgroup label="Opened MT4 Account">
                                @foreach($users['yes'] as $c2)
                                    <option value="{{ $c1->email }}">{{ $c1->email }}</option>
                                @endforeach
                            </optgroup>
                            <optgroup label="No MT4 Account">
                                @foreach($users['no'] as $c3)
                                    <option value="{{ $c2->email }}">{{ $c2->email }}</option>
                                @endforeach
                            </optgroup>
                        </select>
                        @if($errors->has('users'))
                            <span class="text-danger text-md-left">{{ $errors->first('users') }}</span>
                        @endif
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Send Email</button>
        </form>
    </div>
@endsection
@section('javascript')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#example-enableCollapsibleOptGroups-enableClickableOptGroups-enableFiltering-includeSelectAllOption').multiselect({
                enableClickableOptGroups: true,
                enableCollapsibleOptGroups: true,
                enableFiltering: true,
                includeSelectAllOption: true,
                buttonWidth: '100%'
            });
        });
    </script>
@endsection
