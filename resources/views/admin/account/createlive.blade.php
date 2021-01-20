@extends('layouts.base')

@section('css')
<link href="{{ asset('css/boostrap-chosen.css') }}" rel="stylesheet">
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
    <form method="post" action="{{ route('account.live.open') }}">
        @csrf
        <div class="form-row">
            <div class="form-group col-md-6">
                <label>Customer</label>
                <select class="form-control" name="customer">
                    <option value="">Choose one customer</option>
                    @foreach($users as $user)
                    @if(old('customer') == $user->id)
                    <option value="{{ $user->id }}" selected>{{ $user->email . '-' . $user->phone_number }}</option>
                    @else
                    <option value="{{ $user->id }}">{{ $user->email . '-' . $user->phone_number }}</option>
                    @endif
                    @endforeach
                </select>
                @if($errors->has('customer'))
                <span class="text-danger text-md-left">{{ $errors->first('customer') }}</span>
                @endif
            </div>
            <div class="form-group col-md-6">
                <label>Group</label>
                <select class="form-control" name="group">
                    <option value="">Select one group</option>
                    @foreach(config('mt4.group') as $key => $group)
                        @if(old('group') == $key)
                            <option value="{{$key}}" selected>{{$group}}</option>
                        @else
                            <option value="{{$key}}">{{$group}}</option>
                        @endif
                    @endforeach
                </select>
                @if($errors->has('group'))
                <span class="text-danger text-md-left">{{ $errors->first('group') }}</span>
                @endif
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label>Leverage</label>
                <select class="form-control" name="leverage">
                    <option value="">Select one leverage</option>
                    @foreach(config('mt4.leverage') as $key => $leverage)
                        @if(old('leverage') == $key)
                            <option value="{{$key}}" selected>{{$leverage}}</option>
                        @else
                            <option value="{{$key}}">{{$leverage}}</option>
                        @endif
                    @endforeach
                </select>
                @if($errors->has('leverage'))
                <span class="text-danger text-md-left">{{ $errors->first('leverage') }}</span>
                @endif
            </div>
            <div class="form-group col-md-6">
                <label>IB ID</label>
                <input class="form-control" type="text" name="ib_id" value="{{ old('ib_id') }}">
                @if($errors->has('ib_id'))
                <span class="text-danger text-md-left">{{ $errors->first('ib_id') }}</span>
                @endif
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Open account</button>
    </form>
</div>

@endsection
