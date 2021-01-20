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
    <a style="margin-bottom: 40px" href="{{ route('user.create') }}" class="btn btn-info">Thêm mới</a>
    <div class="table-responsive">
        <table class="table table-striped" data-pagination="true">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Full Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Phone number</th>
                    <th scope="col">Address</th>
                    <th scope="col">Country</th>
                    <th scope="col">Copy_of_id</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($userList as $key => $user)
                <tr>
                    <th scope="row">{{ $key + 1 }}</th>
                    <td>{{ $user->full_name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->phone_number }}</td>
                    <td>{{ $user->address }}</td>
                    <td>{{ $user->country }}</td>
                    <td>
                        <img style="height: 75px" src="{{ $user->copy_of_id }}">
                    </td>
                    <td style="width: 14%">
                        <a href="{{ route('user.detail', $user->id) }}" class="btn btn-sm btn-success bold uppercase"
                            title="Edit"><i class="fa fa-edit"></i> </a>
                        <button class="btn btn-sm btn-danger bold uppercase" data-toggle="modal"
                            data-target="#deleteUser"><i class="fa fa-trash-o" aria-hidden="true"></i> </button>
                    </td>
                </tr>
                <!-- Modal -->
                <div class="modal fade" id="deleteUser" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Xoá khách hàng</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                Bạn có chắc chắn muốn xóa khách hàng {{ $user->full_name }}cùng với các tài khoản của họ không ?
                            </div>
                            <div class="modal-footer">
                                <form method="post" action="{{ route('user.delete', $user->id) }}">
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
    {!! $userList->links() !!}
</div>
@endsection
