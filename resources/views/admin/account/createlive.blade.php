@extends('layouts.base')

@section('css')
<link href="{{ asset('css/boostrap-chosen.css') }}" rel="stylesheet">
@endsection

@section('content')

<div class="container-fluid">
    <form>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="firstName">Customer</label>
                <select class="form-control" name="email">
                    @foreach($users as $user)
                        <option value="">Choose one customer</option>
                        @if(old('email') == $user->email)
                            <option value="{{ $user->email }}" selected>{{ $user->email . '-' . $user->phone_number }}</option>
                        @else
                            <option value="{{ $user->email }}">{{ $user->email . '-' . $user->phone_number }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-6">
                <label for="email">Group</label>
                <select class="form-control" name="group">
                    <option value="">Select one group</option>
                    @foreach(config('mt4.group') as $key => $group)
                        <option value="{{$key}}">{{$group}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="email">Leverage</label>
                <select class="form-control" name="leverage">
                    <option value="">Select one leverage</option>
                    @foreach(config('mt4.leverage') as $key => $leverage)
                        <option value="{{$key}}">{{$leverage}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Open account</button>
    </form>
</div>

@endsection