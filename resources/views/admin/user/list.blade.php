@extends('layouts.base')

@section('content')

    <div class="container-fluid">
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
                        <td>
                        <td style="width: 14%">
                            <a href="{{ route('user.detail', $user->id) }}" class="btn btn-sm btn-success bold uppercase" title="Edit"><i class="fa fa-edit"></i> </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
     {!! $userList->links() !!}
    </div>

@endsection

@section('javascript')

    <script src="{{ asset('js/Chart.min.js') }}"></script>
    <script src="{{ asset('js/coreui-chartjs.bundle.js') }}"></script>
    <script src="{{ asset('js/main.js') }}" defer></script>
@endsection
