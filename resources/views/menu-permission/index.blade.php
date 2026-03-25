@extends('adminlte::page')

@section('title', 'Hak Akses Menu')
@section('content_header')
<h1>Hak Akses Menu per Role</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <p class="mb-0 text-muted"><i class="fas fa-info-circle"></i> Role <strong>superadmin</strong> otomatis memiliki akses ke semua menu.</p>
                </div>
                <div class="card-body">
                    <form action="{{ route('menu-permission.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="bg-primary">
                                    <tr>
                                        <th>Menu</th>
                                        @foreach ($roles as $role)
                                        <th class="text-center">{{ ucfirst($role->name) }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($menus as $menu)
                                    <tr>
                                        <td>{{ $menu->name }}</td>
                                        @foreach ($roles as $role)
                                        <td class="text-center">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox"
                                                    class="custom-control-input"
                                                    id="perm_{{ $role->id }}_{{ $menu->id }}"
                                                    name="permissions[{{ $role->id }}][]"
                                                    value="{{ $menu->id }}"
                                                    {{ isset($permissions[$role->id][$menu->id]) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="perm_{{ $role->id }}_{{ $menu->id }}"></label>
                                            </div>
                                        </td>
                                        @endforeach
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="text-right mt-3">
                            <button type="submit" class="btn btn-success">
                                <i class="fa fa-save"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
