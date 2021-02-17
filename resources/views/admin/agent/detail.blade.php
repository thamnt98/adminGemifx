@extends('layouts.base')
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
        <form method="post" action="{{ route('agent.update', $agent->id) }}">
            @csrf
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>IB ID</label>
                    <input class="form-control" type="text" value="{{ $agent->ib_id }}" disabled>
                </div>
                <div class="form-group col-md-6">
                    <label>Full name</label>
                    <input class="form-control" type="text" value="{{ old('name', $agent->name) }}" name="name">
                    @if($errors->has('name'))
                        <span class="text-danger text-md-left">{{ $errors->first('name') }}</span>
                    @endif
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Email</label>
                    <input class="form-control" type="text" value="{{ $agent->email }}" disabled>
                </div>
                <div class="form-group col-md-6">
                    <label>Phone number</label>
                    <input class="form-control" type="text" value="{{ old('phone_number', $agent->phone_number) }}" name="phone_number">
                    @if($errors->has('phone_number'))
                        <span class="text-danger text-md-left">{{ $errors->first('phone_number') }}</span>
                    @endif
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Role</label>
                    <input class="form-control" type="text" value="@if(is_null($agent->admin_id)) Manager @else Staff @endif" disabled>
                </div>
                <div class="form-group col-md-6">
                    <label>Status</label>
                    <select class="form-control" name="status">
                        <option value="1" @if(old('status', $agent->status) == 1) selected @endif>Verified</option>
                        <option value="2" @if(old('status', $agent->status) == 2) selected @endif>Unverified</option>
                    </select>
                    @if($errors->has('status'))
                        <span class="text-danger text-md-left">{{ $errors->first('status') }}</span>
                    @endif
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Commsion ($/lots)</label>
                    <input class="form-control" type="text" value="{{ old('commission', $agent->commission) }}" name="commission">
                    @if($errors->has('commission'))
                        <span class="text-danger text-md-left">{{ $errors->first('commission') }}</span>
                    @endif
                </div>
                @if(is_null($agent->admin_id))
                    <div class="form-group col-md-6">
                        <label>Staff Commisison ($/lots)</label>
                        <input class="form-control" type="text" value="{{ old('staff_commission', $agent->staff_commission) }}" name="staff_commission">
                        @if($errors->has('staff_commission'))
                            <span class="text-danger text-md-left">{{ $errors->first('staff_commission') }}</span>
                        @endif
                    </div>
                @endif
            </div>
            <button type="submit" class="btn btn-primary " style="margin-top: 20px">Cập nhật</button>
        </form>
    </div>
@endsection
