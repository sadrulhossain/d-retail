@extends('admin.index')
@section('title')
Admin Dashboard
@endsection
@section('dashboardTitle')
<div class="breadcrumbs">
    <div class="col-sm-4">
        <div class="page-header float-left">
            <div class="page-title">
                <h1>User</h1>
            </div>
        </div>
    </div>

</div>
@endsection
@section('content')
<div class="content mt-3">
    @if(session('status'))
    <div class="col-sm-12">
        <div class="alert  alert-success alert-dismissible fade show" role="alert">
            <span>{{session('status')}}</span>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    </div>
    @endif

    <div class="card">
        <div class="card-header">
            <strong class="card-title">User List</strong>
            <span class="float-right">
                <a href="{{ route('register') }}" class="btn btn-warning">{{__('lang.ADDNEW')}}</a>
            </span>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    @foreach($users as $user)
                    <tr>
                        <td>{{$i}}</td>
                        <td>{{$user->name}}</td>
                        <td>{{$user->email}}</td>
                        <td>
                            <a href="{{route('userEdit',$user->id)}}" class="btn btn-success">{{__('lang.EDIT')}}</a>
                            <a href="{{route('userDelete',$user->id)}}" class="btn btn-danger">{{__('lang.DELETE')}}</a>
                        </td>
                    </tr>
                    <?php $i++; ?>
                    @endforeach

                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

