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
                    <th scope="col">Role</th>
                    <th scope="col">Status</th>
                    @if(\Illuminate\Support\Facades\Auth::user()->role == config('role.admin'))
                        <th></th>
                    @endif
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
                        <th scope="row">
                            @if(is_null($agent->admin_id))
                                Manager
                            @else
                                Staff
                            @endif
                        </th>
                        <th>
                            @if(\Illuminate\Support\Facades\Auth::user()->role == config('role.admin'))
                                @if($agent->status == 1)
                                    <a style="color:white" class="btn btn-dark bold btn-active" data-toggle="modal"
                                       data-target="#active" data-id="{{ $agent->id }}" data-status="2"
                                       style="width:150px">Verified</a>
                                @else
                                    <a style="color:white" class="btn btn-success bold btn-active" data-toggle="modal"
                                       data-target="#active" data-id="{{ $agent->id }}" data-status="1"
                                       style="width:150px">Unverified</a>
                                @endif

                            @else
                                @if($agent->status == 1)
                                    <button type="button" class="btn btn-dark" disabled>Verified</button>
                                @else
                                    <button type="button" class="btn btn-success" disabled>Unverified</button>
                                @endif
                            @endif
                        </th>
                        @if(\Illuminate\Support\Facades\Auth::user()->role == config('role.admin'))
                            <th>
                                <a href="{{ route('agent.detail', $agent->id) }}"
                                   class="btn btn-sm btn-success bold uppercase" title="Edit"><i class="fa fa-edit"></i>
                                </a>
                            </th>
                        @endif
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        {!! $agents->appends(request()->input())->links() !!}
    </div>
    <div class="modal fade" id="active" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Xác nhận</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body"></div>
                <div class="modal-footer">
                    <form method="post" id="active-agent">
                        @csrf
                        <a href="#" class="btn btn-secondary" data-dismiss="modal">No</a>
                        <a href="#" onclick="$(this).closest('form').submit();" class="btn btn-primary">Yes</a>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('javascript')
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/boostrap-datepicker.js') }}"></script>
    <script>
        $('.btn-active').on('click', function () {
            let currentUrl = window.location.origin
            let id = $(this).attr('data-id');
            let status = $(this).attr('data-status');
            if(status == 1){
                $('.modal-body').html('Bạn có muốn kích hoạt người này không ? ')
            }else
            {
                $('.modal-body').html('Bạn có muốn hủy kích hoạt người này không ? ')
            }
            let redirectUrl = currentUrl + '/admin/agent/active/' + id + '?status=' + status;
            $("#active-agent").attr('action', redirectUrl);
        })
    </script>
@endsection

