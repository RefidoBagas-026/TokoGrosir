@extends('adminlte::page')

@section('title', 'Edit Role')
@section('content_header')
<h1>Edit Role</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('role.update', $role->id, false) }}" method="post">
                        @csrf
                        @method('PUT')

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">Nama Role</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $role->name) }}" required>
                                @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12 text-right">
                            <button type="submit" class="btn btn-success">
                                <i class="fa fa-save"></i>
                                Save</button>
                            <a href="{{ route('role.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
