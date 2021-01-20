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
    <form method="post" action="{{ route('account.live.update', $account->id) }}">
        @csrf
        <div class="form-row">
            <div class="form-group col-md-6">
                <label>Login</label>
                <input class="form-control" type="text" value="{{ $account->login }}" disabled>
            </div>
            <div class="form-group col-md-6">
                <label>Group</label>
                <select class="form-control" name="group">
                    <option value="">Select one group</option>
                    @foreach(config('mt4.group') as $key => $group)
                        @if(old('group', $account->group) == $key)
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
                        @if(old('leverage', $account->leverage) == $key)
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
                <input class="form-control" type="text" value="{{ old('ib_id', $account->ib_id) }}" name="ib_id">
                @if($errors->has('ib_id'))
                    <span class="text-danger text-md-left">{{ $errors->first('ib_id') }}</span>
                @endif
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label>Full name</label>
                <input class="form-control" type="text" value="{{ $account->user->full_name }}" disabled>
            </div>
            <div class="form-group col-md-6">
                <label>Phone number</label>
                <input class="form-control" type="text" name="phone"  value="{{ old('phone', $account->phone_number) }}">
                @if($errors->has('phone'))
                    <span class="text-danger text-md-left">{{ $errors->first('phone') }}</span>
                @endif
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label>Zip code</label>
                <input class="form-control" type="text" value="{{ $account->user->zip_code }}" disabled>
            </div>
            <div class="form-group col-md-6">
                <label>City</label>
                <input class="form-control" type="text"  value="{{ $account->city }}" disabled>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label>State </label>
                <input class="form-control" type="text" value="{{ $account->user->state }}" disabled>
            </div>
            <div class="form-group col-md-6">
                <label>Address</label>
                <input class="form-control" type="text" value="{{ $account->address }}" disabled>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label>Country</label>
                <input class="form-control" type="text" value="{{ $account->user->state }}" disabled>
                @if($errors->has('country'))
                    <span class="text-danger text-md-left">{{ $errors->first('country') }}</span>
                @endif
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>

@endsection
