@extends('adminlte::page')

@section('title', 'Buat Role Baru')
@section('content_header')
<h1>Buat Role Baru</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('role.store', [], false) }}" method="post">
                        @csrf

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">Nama Role</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12 text-right">
                            <button type="submit" class="btn btn-success">
                                <i class="fa fa-save"></i>
                                Simpan</button>
                            <a href="{{ route('role.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
