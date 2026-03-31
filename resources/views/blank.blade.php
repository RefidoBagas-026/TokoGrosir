@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="d-flex justify-content-center align-items-center" style="height: 60vh;">
        <div class="text-center">
            <h1 class="display-3 font-weight-bold mb-3" style="letter-spacing: 2px; color: #007bff; text-shadow: 2px 2px 8px #b3d1ff; animation: fadeInDown 1s;">
                WELCOME TO THE DASHBOARD
            </h1>
            <p class="lead" style="color: #333; animation: fadeInUp 1.5s;">
                Selamat datang di aplikasi <span class="font-italic" style="color: #17a2b8;">Inventory Sistem</span>!<br>
                Kelola data dan transaksi Anda dengan mudah dan efisien.
            </p>
        </div>
    </div>
    <style>
        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-40px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
@endsection