@extends('layouts.base')

@section('content')

    <div class="container-fluid">
        <form>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="firstName">First name</label>
                    <input type="text" class="form-control" id="firstName" name="first_name" value="{{ old('first_name', $user->first_name) }}">
                </div>
                <div class="form-group col-md-6">
                    <label for="lastName">Last name</label>
                    <input type="text" class="form-control" id="lastName" name="last_name" value="{{ old('last_name', $user->last_name) }}">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}">
                </div>
                <div class="form-group col-md-6">
                    <label for="phoneNumber">Phone Number</label>
                    <input type="text" class="form-control" id="phoneNumber" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="city">City</label>
                    <input type="text" class="form-control" id="city" name="city" value="{{ old('city', $user->city) }}">
                </div>
                <div class="form-group col-md-6">
                    <label for="state">State</label>
                    <input type="text" class="form-control" id="state" name="state" value="{{ old('state', $user->state) }}">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="zipCode">Zip code</label>
                    <input type="text" class="form-control" id="zipCode" name="zip_code" value="{{ old('zip_code', $user->zip_code) }}">
                </div>
                <div class="form-group col-md-6">
                    <label for="country">State</label>
                    <select id="country" class="form-control">
                        <option selected>Choose...</option>
                        @foreach(config('country') as $key => $country)
                            @if(old('country', $user->country) == $key)
                                <option value="{{ $key }}" selected>{{ $country }}</option>
                            @else
                                <option value="{{ $key }}">{{ $country }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="address">Address</label>
                    <input type="text" class="form-control" id="address" name="address" value="{{ old('address', $user->address) }}">
                </div>
                <div class="form-group col-md-6">
                    <label for="copyOfId">Copy of id</label>
                    <input type="file" class="form-control-file" id="copyOfId" name="copy_of_id">
                    <img style="margin-top:20px; height: 75px"  src="{{ $user->copy_of_id }}" style="height: 75px">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="proofOfAddress">Proof of address</label>
                    <input type="file" class="form-control-file" id="proofOfAddress" name="proof_of_address">
                    <img style="margin-top:20px; height: 75px" src="{{ $user->proof_of_address }}" style="height: 75px">
                </div>
                <div class="form-group col-md-6">
                    <label for="addtionFile">Addtional file</label>
                    <input type="file" class="form-control-file" id="addtionFile" name="addtional_file">
                    <img style="margin-top:20px; height: 75px" src="{{ $user->addtional_file }}" style="height: 75px">
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Cập nhât</button>
        </form>
    </div>

@endsection