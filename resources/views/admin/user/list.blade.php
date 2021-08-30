@extends('layouts.base')

@section('content')
<style>
    .table_td{
        display: flex;
    }
    .table_td a{
        margin-right: 5px;
    }
</style>
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
            @can('user.create')
                <a style="margin-bottom: 40px" href="{{ route('user.store') }}" class="btn btn-info">Thêm mới</a>
            @endcan
        <div class="form-search row">
            <div class="col-md-2"></div>
            <div class="col-md-8">
                <form method="get" action="{{ route('user.list') }}">
                    @csrf
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <input class="form-control" type="text" name="email" value="{{ $data['email'] ?? '' }}"
                                   style="height: 40px" placeholder="Email">
                        </div>
                        <div class="form-group col-md-4">
                            <input class="form-control" type="text" name="login" value="{{ $data['login'] ?? '' }}"
                                   style="height: 40px" placeholder="Login">
                        </div>
                        <div class="form-group col-md-4">
                            <input class="form-control" type="text" name="ib_id" value="{{ $data['ib_id'] ?? '' }}"
                                   style="height: 40px" placeholder="IB ID">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary" style="margin-top: 10px">Search</button>
                </form>
            </div>
            <div class="col-md-2"></div>
        </div>
        <div class="table-responsive" style="margin-top: 70px">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">IB ID</th>
                    <th scope="col">Full Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Phone number</th>
                    <th scope="col">Address</th>
                    <th scope="col">Country</th>
                    <th scope="col">Copy_of_id</th>
                    <th scope="col">Verify profile</th>
                    <th scope="col">Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach($userList as $key => $user)
                    <tr>
                        <th scope="row">{{ $key + 1 }}</th>
                        <th>{{ $user->ib_id }}</th>
                        <td>{{ $user->full_name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->phone_number }}</td>
                        <td>{{ $user->address }}</td>
                        <td>{{ $user->country }}</td>
                        <td>
                            <img style="height: 75px" src="{{ $user->copy_of_id }}">
                        </td>
                        <td>
                            @if($user->check_active == 1)
                                <span style="color: #2eb85c; font-weight: bold">Confirm </span>
                            @else
                                <span style="color: black; font-weight: bold">Unconfirmed</span>
                            @endif
                        </td>
                        <td class="table_td">
                            <a href="{{ route('user.detail', $user->id) }}"
                               class="btn btn-sm btn-success bold uppercase"
                               title="Edit"><i class="fa fa-edit"></i> </a>
                            @can('user.delete')
                                <a style="color:white" class="btn btn-sm btn-danger bold uppercase btn-delete-user"
                                   data-toggle="modal" data-target="#deleteUser" data-id="{{ $user->id }}"
                                   target="delete"
                                   data-name="{{ $user->full_name }}"><i class="fa fa-trash-o" aria-hidden="true"></i> </a>
                                <a style="color:white" class="btn btn-sm btn-primary bold uppercase btn-active-user"
                                    title="Active"
                                   data-toggle="modal" data-target="#activeUser" data-id="{{ $user->id }}"
                                   data-name="{{ $user->full_name }}">Approved </a>
                                <a style="color:white" class="btn btn-sm btn-warning bold uppercase btn-inactive-user"
                                    title="Inactive"
                                    target="inactive"
                                   data-toggle="modal" data-target="#inActiveUser" data-id="{{ $user->id }}"
                                   data-name="{{ $user->full_name }}">Reject </a>
                            @endcan
                        </td>
                    </tr>
                    <!-- Modal -->
                @endforeach
                </tbody>
            </table>
        </div>
        {!! $userList->appends(request()->input())->links() !!}
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
                <div class="modal-body modal-body-delete"></div>
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

    <div class="modal fade" id="activeUser" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
   <div class="modal-dialog" role="document">
       <div class="modal-content">
           <div class="modal-header">
               <h5 class="modal-title" id="exampleModalLabel">Xác thực người dùng</h5>
               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                   <span aria-hidden="true">&times;</span>
               </button>
           </div>
           <div class="modal-body modal-body-active"></div>
           <div class="modal-footer">
               <form method="post" id="active-user">
                   @csrf
                   <a href="#" class="btn btn-secondary" data-dismiss="modal">Hủy</a>
                   <a href="#" onclick="$(this).closest('form').submit();" class="btn btn-primary">Xác thực</a>
               </form>
           </div>
       </div>
   </div>
</div>

<div class="modal fade" id="inActiveUser" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
aria-hidden="true">
<div class="modal-dialog" role="document">
   <div class="modal-content">
       <div class="modal-header">
           <h5 class="modal-title" id="exampleModalLabel">Hủy xác thực người dùng</h5>
           <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
           </button>
       </div>
       <div class="modal-body modal-body-inactive"></div>
       <div class="modal-footer">
           <form method="post" id="inactive-user">
               @csrf
               <a href="#" class="btn btn-secondary" data-dismiss="modal">Hủy</a>
               <a href="#" onclick="$(this).closest('form').submit();" class="btn btn-primary">Hủy xác thực</a>
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
            console.log('abc');
            let currentUrl = window.location.origin
            let id = $(this).attr('data-id');
            let name = $(this).attr('data-name');
            $('.modal-body-delete').html("Bạn có muốn xóa khách hàng " + name +
                " cùng với tất cả tài khoản của họ không ?");
            let redirectUrl = currentUrl + '/admin/user/delete/' + id;
            $("#delete-user").attr('action', redirectUrl);
        })

        $('.btn-active-user').on('click', function () {
            let currentUrl = window.location.origin
            let id = $(this).attr('data-id');
            let name = $(this).attr('data-name');
            $('.modal-body-active').html("Bạn có muốn đồng ý xác thực " + name +
                " cùng với tất cả tài khoản của họ không ?");
            let redirectUrl = currentUrl + '/admin/user/active/' + id;
            $("#active-user").attr('action', redirectUrl);
        })

        $('.btn-inactive-user').on('click', function () {
            let currentUrl = window.location.origin
            let id = $(this).attr('data-id');
            let name = $(this).attr('data-name');
            $('.modal-body-inactive').html("Bạn có muốn đồng ý hủy xác thực " + name +
                " cùng với tất cả tài khoản của họ không ?");
            let redirectUrl = currentUrl + '/admin/user/inactive/' + id;
            $("#inactive-user").attr('action', redirectUrl);
        })

    </script>
@endsection
