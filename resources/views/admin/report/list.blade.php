@extends('layouts.base')
@section('css')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.23/css/dataTables.bootstrap4.min.css" />
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css" />
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
            <table id="example" class="table table-striped" style="width:100%" data-search="false">
                <div>
                    <b>Lots: {{ $lots  }} </b>
                </div>
                <div>
                    <b>Commision: </b>
                </div>
                <br>
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th>Login</th>
                    <th>Order</th>
                    <th>Type</th>
                    <th>Symbol</th>
                    <th>Lots</th>
                    <th>Open price</th>
                    <th>Close price</th>
                    <th>Open time</th>
                    <th>Close time</th>
                    <th>Profit</th>
                </tr>
                </thead>
                <tbody>
                @foreach($trades as $key => $trade)
                    <tr>
                        <th scope="row">{{ $key + 1 }}</th>
                        <td>{{ $trade[0] }}</td>
                        <td>{{ $trade[1] }}</td>
                        <td>{{ config('mt4.cmd')[$trade[12]] }}</td>
                        <td>{{ $trade[2] }}</td>
                        <td>{{ round($trade[6]/100, 2) }}</td>
                        <td>{{ $trade[3] }}</td>
                        <td>{{ $trade[4] }}</td>
                        <td>{{ date('Y-m-d H:i:s', $trade[7]) }}</td>
                        <td>{{ date('Y-m-d H:i:s', $trade[8]) }}</td>
                        <td>{{ $trade[5] }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
@section('javascript')
    <script type="text/javascript" src="{{ asset('js/jquery.min.js') }}" ></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script type="text/javascript" src=" https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src=" https://cdn.datatables.net/1.10.23/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $('input[name="close_time"]').daterangepicker(
            {
                startDate:  moment().clone().startOf('month').format('YYYY/MM/DD'),
                locale: {
                    format: 'YYYY/MM/DD'
                }
            }
        );
        $(document).ready(function() {
            $('#example').DataTable(
                {
                    searching:false,
                    columnDefs : [
                        { targets: 0, sortable: false},
                    ],
                    order: [[ 1, "asc" ]]
                }
            );
        } );
    </script>
@endsection
