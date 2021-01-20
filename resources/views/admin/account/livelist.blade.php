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
    <a style="margin-bottom: 40px" href="{{ route('account.live.create') }}" class="btn btn-info">Thêm mới</a>
    <div class="table-responsive">
        <table class="table table-striped" data-pagination="true">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Login</th>
                    <th scope="col">Email</th>
                    <th scope="col">Full Name</th>
                    <th scope="col">Group</th>
                    <th scope="col">Leverage</th>
                    <th scope="col">IB ID</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($accountList as $key => $account)
                <tr>
                    <th scope="row">{{ $key + 1 }}</th>
                    <td>{{ $account->login }}</td>
                    <td>{{ $account->user->email }}</td>
                    <td>{{ $account->user->full_name }}</td>
                    <td>{{ $account->group }}</td>
                    <td>{{ $account->leverage }}</td>
                    <td>{{ $account->ib_id }}</td>
                    <td style="width: 14%">
                        <a href="{{ route('account.live.detail', $account->id) }}"
                            class="btn btn-sm btn-success bold uppercase" title="Edit"><i class="fa fa-edit"></i> </a>
                        <a style="color:white" class="btn btn-sm btn-danger bold uppercase btn-delete-account "
                            data-toggle="modal" data-login="{{  $account->login }}"
                            data-name="{{ $account->user->full_name }}" data-target="#deleteAccount"><i
                                class="fa fa-trash-o" aria-hidden="true"></i> </a>
                    </td>
                </tr>

                @endforeach
            </tbody>
        </table>
    </div>
    {!! $accountList->links() !!}
</div>
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
            <div class="modal-body"></div>
            <div class="modal-footer">
                <form method="post" id="delete-account" action="">
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
    $('.btn-delete-account').on('click', function () {
        let currentUrl = window.location.origin
        let login = $(this).attr('data-login');
        let name = $(this).attr('data-name');
        $('.modal-body').html("Bạn có muốn xóa tài khoản này của khách hàng " + name + " không ?");
        let redirectUrl = currentUrl + '/admin/account/delete/' + login;
        $("#delete-account").attr('action', redirectUrl);
    })

</script>
@endsection
