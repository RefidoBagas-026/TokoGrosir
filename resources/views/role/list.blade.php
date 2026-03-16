@extends('adminlte::page')

@section('title', 'Daftar Role')
@section('content_header')
<h1>Daftar Role</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">

                    <div class="float-right">
                        <a href="{{ route('role.create') }}" class="btn btn-success">
                            <i class="fas fa-plus"></i>
                            Create</a>
                    </div>

                    <table id="roleTable" class="table table-striped">
                        <thead>
                            <tr>
                                <th data-orderable="false" scope="col">#</th>
                                <th scope="col">Nama Role</th>
                                <th scope="col">Jumlah User</th>
                                <th data-orderable="false" scope="col" width="350px">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($roles as $role)
                            <tr>
                                <th scope="row">{{ ++$i }}</th>
                                <td>{{ $role->name }}</td>
                                <td>{{ $role->users()->count() }}</td>
                                <td>
                                    <div class="d-flex justify-content-around">
                                        <a href="{{ route('role.edit', $role->id) }}" class="btn btn-primary">
                                            <i class="fas fa-edit"></i>
                                            Edit</a>
                                        <form action="{{ route('role.destroy', $role->id, false) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus role ini?')">
                                                <i class="fas fa-trash"></i>
                                                Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div>
                        {{ $roles->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('js')
<script>
    $(document).ready(function() {
        $('#roleTable').DataTable({
            "order": [
                [0, "desc"]
            ],
            "columnDefs": [{
                "orderable": true,
                "targets": [0, 1, 2]
            }],
            "paging": false,
            "info": false,
        });
    });
</script>
@stop
