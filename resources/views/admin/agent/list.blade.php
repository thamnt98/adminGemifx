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
        <div class="table-responsive">
            <table class="table table-striped" data-pagination="true">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">IB ID</th>
                    <th scope="col">Full Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Phone number</th>
                </tr>
                </thead>
                <tbody>
                @foreach($agents as $key => $agent)
                    <tr>
                        <th scope="row">{{ $key + 1 }}</th>
                        <th scope="row">{{ $agent->ib_id }}</th>
                        <th scope="row">{{ $agent->name }}</th>
                        <th scope="row">{{ $agent->email }}</th>
                        <th scope="row">{{ $agent->phone_number }}</th>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        {!! $agents->appends(request()->input())->links() !!}
    </div>
@endsection

