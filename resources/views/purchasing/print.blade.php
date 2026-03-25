@extends('adminlte::page')

@section('title', 'Print Nota Pembelian')

@section('content')
<div class="container pt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header text-center">
                    <h4>Bukti Terima</h4>
                </div>
                <div class="card-body">
                    <table>
                        <tr>
                            <td><strong>Tanggal Pembelian</strong></td>
                            <td>: {{ date('d-M-Y', strtotime($purchase->date)) }}</td>
                        </tr>
                        <tr>
                            <td><strong>Supplier</strong></td>
                            <td>: {{ $purchase->supplierName }}</td>
                        </tr>
                    </table>
                    <hr>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Qty</th>
                                <th>Satuan</th>
                                <th>Harga Beli</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $purchase->productName }}</td>
                                <td>{{ $purchase->purchaseQty }}</td>
                                <td>{{ $purchase->purchaseUom }}</td>
                                <td>Rp {{ number_format($purchase->purchasePrice, 0, ',', '.') }}</td>
                             </tr>
                        </tbody>
                    </table>

                    <hr>
                    
                    <div class="mb-5 d-flex flex-column justify-content-center align-items-end">
                        <h5 class="mb-4"><strong>Total: Rp {{ number_format($purchase->purchasePrice, 0, ',', '.') }}</strong></h5>
                        <div class="text-center" style="padding: 0 20px;">
                           <p>Penerima Barang</p>
                            <br><br><br>
                            <p class="mb-0">{{ $purchase->created_by }}</p>
                            <p><strong>Ruko Telur KSM Purbayan</strong></p>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-center">
                    <button onclick="window.print()" class="btn btn-primary"><i class="fas fa-print"></i> Print</button>
                    <a href="{{ route('purchasing.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('css')
<style>
    @media print {

        .card-footer,
        .btn {
            display: none !important;
        }

        body {
            margin: 0;
            padding: 0;
            background-color: white !important;
        }
    }
</style>
@endsection