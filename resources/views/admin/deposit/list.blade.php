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
        <div class="form-search row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <form method="get" action="{{ route('deposit.list') }}">
                    @csrf
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <input class="form-control" type="text" name="email" value="{{ $data['email'] ?? '' }}"
                                   style="height: 40px" placeholder="Email">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary" style="margin-top: 10px">Search</button>
                </form>
            </div>
            <div class="col-md-3"></div>
        </div>
        <div class="table-responsive">
            <table class="table table-striped" data-pagination="true">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th>Email</th>
                    <th>Name</th>
                    <th>Amount Money</th>
                    <th>Type</th>
                    <th>Transaction Date</th>
                    <th>Bank Name</th>
                    <th>Status</th>
                </tr>
                </thead>
                <tbody>
                @foreach($orders as $key => $order)
                    <tr>
                        <th scope="row">{{ $key + 1 }}</th>
                        <td>{{ $order->user->email }}</td>
                        <td>{{ $order->user->full_name }}</td>
                        <td>{{ number_format($order->amount_money) }}</td>
                        <td>{{ config('deposit.type_text')[$order->type] }}</td>
                        <td>{{ $order->created_at }}</td>
                        <td>{{ $order->bank_name }}</td>
                        <td>
                            @if($order->status == config('deposit.status.yes'))
                                <button type="button" class="btn btn-dark"
                                        disabled>{{ config('deposit.status_text')[$order->status] }}</button>
                            @else
                                <a style="color:white" class="btn btn-success bold btn-approve" data-toggle="modal"
                                   data-target="#approve" data-id="{{ $order->id }}"
                                   style="width:150px">{{ config('deposit.status_text')[$order->status] }}</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        {!! $orders->appends(request()->input())->links() !!}
    </div>
    <div class="modal fade" id="approve" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Xác nhận</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">Bạn có chắc chắn xác nhận không ?</div>
                <div class="modal-footer">
                    <form method="post" id="approve-order">
                        @csrf
                        <a href="#" class="btn btn-secondary" data-dismiss="modal">Hủy</a>
                        <a href="#" onclick="$(this).closest('form').submit();" class="btn btn-primary">Approve</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('javascript')
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script>
        $('.btn-approve').on('click', function () {
            let currentUrl = window.location.origin
            let id = $(this).attr('data-id');
            let redirectUrl = currentUrl + '/admin/deposit/approve/' + id;
            $("#approve-order").attr('action', redirectUrl);
        })

    </script>
@endsection
