@extends('layouts.base')
@section('css')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endsection
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
            <div class="col-md-1"></div>
            <div class="col-md-10">
                <form method="get" action="{{ route('report.trade') }}">
                    @csrf
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="dates">Close time</label>
                            <input type="text" class="form-control" name="close_time" value="{{ $closeTime }}" id="dates"/>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary" style="margin-top: 10px">Search</button>
                </form>
            </div>
            <div class="col-md-1"></div>
        </div>
        <div class="table-responsive" style="margin-top: 70px">
            <table class="table table-striped" data-pagination="true">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th>Login</th>
                    <th>Order</th>
                    <th>Symbol</th>
                    <th>Open price</th>
                    <th>Close price</th>
                    <th>Profit</th>
                    <th>Volume</th>
                    <th>Open time</th>
                    <th>Close time</th>
                    <th>Comment</th>
                    <th>Commision</th>
                    <th>Agent Commision</th>
                    <th>Cmd</th>
                    <th>SL</th>
                    <th>Tp</th>
                    <th>Swap</th>
                </tr>
                </thead>
                <tbody>
                @foreach($trades as $key => $trade)
                    <tr>
                        <th scope="row">{{ $key + 1 }}</th>
                        <td>{{ $trade[0] }}</td>
                        <td>{{ $trade[1] }}</td>
                        <td>{{ $trade[2] }}</td>
                        <td>{{ $trade[3] }}</td>
                        <td>{{ $trade[4] }}</td>
                        <td>{{ $trade[5] }}</td>
                        <td>{{ $trade[6] }}</td>
                        <td>{{ $trade[7] }}</td>
                        <td>{{ $trade[8] }}</td>
                        <td>{{ $trade[9] }}</td>
                        <td>{{ $trade[10] }}</td>
                        <td>{{ $trade[11] }}</td>
                        <td>{{ $trade[12] }}</td>
                        <td>{{ $trade[13] }}</td>
                        <td>{{ $trade[14] }}</td>
                        <td>{{ $trade[15] }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
@section('javascript')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script>
        $('input[name="close_time"]').daterangepicker();
    </script>
@endsection
