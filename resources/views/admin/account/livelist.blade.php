@extends('layouts.base')

@section('content')

    <div class="container-fluid">
        <a style="margin-bottom: 40px" href="" class="btn btn-info">Thêm mới</a>
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
                        <td style="width: 14%">
                            <a href="" class="btn btn-sm btn-success bold uppercase" title="Edit"><i class="fa fa-edit"></i> </a>
                            <a href="" class="btn btn-sm btn-danger bold uppercase" title="Delete"><i class="fa fa-trash-o" aria-hidden="true"></i> </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        {!! $accountList->links() !!}
    </div>

@endsection

@section('javascript')
    <script src="{{ asset('js/main.js') }}" defer></script>
@endsection
