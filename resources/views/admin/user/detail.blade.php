@extends('layouts.base')

@section('content')

<div class="container-fluid">
    <section class="mx-2 pb-3">
        <ul class="nav nav-tabs md-tabs" id="myTabMD" role="tablist">
            <li class="nav-item waves-effect waves-light">
                <a class="nav-link active" id="information-tab-md" data-toggle="tab" href="#information-md" role="tab"
                    aria-controls="information-md" aria-selected="true">Information</a>
            </li>
            <li class="nav-item waves-effect waves-light">
                <a class="nav-link" id="account-tab-md" data-toggle="tab" href="#account-md" role="tab"
                    aria-controls="account-md" aria-selected="false">Account</a>
            </li>
        </ul>
        <div class="tab-content card pt-5" id="myTabContentMD">
            <div class="tab-pane fade show active" id="information-md" role="tabpanel" aria-labelledby="information-tab-md" style="margin:40px">
                <form>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="firstName">First name</label>
                            <input type="text" class="form-control" id="firstName" name="first_name"
                                value="{{ old('first_name', $user->first_name) }}">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="lastName">Last name</label>
                            <input type="text" class="form-control" id="lastName" name="last_name"
                                value="{{ old('last_name', $user->last_name) }}">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email"
                                value="{{ old('email', $user->email) }}">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="phoneNumber">Phone Number</label>
                            <input type="text" class="form-control" id="phoneNumber" name="phone_number"
                                value="{{ old('phone_number', $user->phone_number) }}">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="city">City</label>
                            <input type="text" class="form-control" id="city" name="city"
                                value="{{ old('city', $user->city) }}">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="state">State</label>
                            <input type="text" class="form-control" id="state" name="state"
                                value="{{ old('state', $user->state) }}">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="zipCode">Zip code</label>
                            <input type="text" class="form-control" id="zipCode" name="zip_code"
                                value="{{ old('zip_code', $user->zip_code) }}">
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
                            <input type="text" class="form-control" id="address" name="address"
                                value="{{ old('address', $user->address) }}">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="copyOfId">Copy of id</label>
                            <input type="file" class="form-control-file" id="copyOfId" name="copy_of_id">
                            <img style="margin-top:20px; height: 75px" src="{{ $user->copy_of_id }}" style="height: 75px">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="proofOfAddress">Proof of address</label>
                            <input type="file" class="form-control-file" id="proofOfAddress" name="proof_of_address">
                            <img style="margin-top:20px; height: 75px" src="{{ $user->proof_of_address }}"
                                style="height: 75px">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="addtionFile">Addtional file</label>
                            <input type="file" class="form-control-file" id="addtionFile" name="addtional_file">
                            <img style="margin-top:20px; height: 75px" src="{{ $user->addtional_file }}"
                                style="height: 75px">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Cập nhât</button>
                </form>
            </div>
            <div class="tab-pane fade" id="account-md" role="tabpanel" aria-labelledby="account-tab-md" style="margin:40px">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Login</th>
                                <th scope="col">Group</th>
                                <th scope="col">Leverage</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($user->liveAccounts as $key => $liveAccount)
                                <tr>
                                    <th scope="row">{{ $key + 1 }}</th>
                                    <td>{{ $liveAccount->login }}</td>
                                    <td>{{ $liveAccount->leverage }}</td>
                                    <td style="width: 14%">
                                        <a href="{{ route('account.live.detail') }}" class="btn btn-sm btn-success bold uppercase" title="Edit"><i class="fa fa-edit"></i> </a>
                                        <button class="btn btn-sm btn-danger bold uppercase" data-toggle="modal"
                                        data-target="#deleteAccount"><i class="fa fa-trash-o" aria-hidden="true"></i> </button>
                                    </td>
                                </tr>
                                <!-- Modal -->
                                <div class="modal fade" id="deleteAccount" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Xoá tài khoản</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                Bạn có chắc chắn muốn xóa tài khoản này không ?
                                            </div>
                                            <div class="modal-footer">
                                                <form method="post" action="{{ route('account.live.delete', $liveAccount->login) }}">
                                                    @csrf
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                                                    <button type="submit" class="btn btn-primary">Xóa</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
