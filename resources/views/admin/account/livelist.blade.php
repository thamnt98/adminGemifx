@extends('layouts.base')

@section('content')

    <div class="container-fluid">
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
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        {!! $accountList->links() !!}
    </div>

@endsection

@section('javascript')

    <script src="{{ asset('js/Chart.min.js') }}"></script>
    <script src="{{ asset('js/coreui-chartjs.bundle.js') }}"></script>
    <script src="{{ asset('js/main.js') }}" defer></script>
@endsection
