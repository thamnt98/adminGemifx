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
                        <a style="color:white" class="btn btn-sm btn-danger bold uppercase btn-delete-user"
                            data-toggle="modal" data-target="#deleteUser" data-id="{{ $user->id }}"
                            data-name="{{ $user->full_name }}"><i class="fa fa-trash-o" aria-hidden="true"></i> </a>
                    </td>
                </tr>
                <!-- Modal -->
                @endforeach
            </tbody>
        </table>
    </div>
    {!! $userList->links() !!}
</div>
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
            <div class="modal-body"></div>
            <div class="modal-footer">
                <form method="post" id="delete-user">
                    @csrf
                    <a href="#" class="btn btn-secondary" data-dismiss="modal">Hủy</a>
                    <a href="#" onclick="$(this).closest('form').submit();" class="btn btn-primary">Xóa</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('javascript')
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script>
    $('.btn-delete-user').on('click', function () {
        let currentUrl = window.location.origin
        let id = $(this).attr('data-id');
        let name = $(this).attr('data-name');
        $('.modal-body').html("Bạn có muốn xóa khách hàng " + name +
            " cùng với tất cả tài khoản của họ không ?");
        let redirectUrl = currentUrl + '/admin/user/delete/' + id;
        $("#delete-user").attr('action', redirectUrl);
    })

</script>
@endsection
