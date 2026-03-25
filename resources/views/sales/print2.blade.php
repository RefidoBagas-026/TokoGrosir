@extends('adminlte::page')

<!--@section('title', 'Print Nota Jual')-->


@section('content')
<div class="d-flex justify-content-center pt-5" style="min-height: 80vh;">
    
    <div class="card shadow" style="width: 463px;">
        
        <div class="card-body " id="print-area">
            <div class="receipt">
                <div class="center mb-3">
                    <h3 class="m-0">Ruko Telur KSM Purbayan</h3>
                    <p class="m-0">Jl. Purbayan No. 123, Kota XYZ</p>
                </div>
                <hr>

                <p>Tanggal : {{ date('d-M-Y', strtotime($sales->date)) }}</p>
                <p>Pembeli : {{ $sales->buyerName }}</p>
                <hr>

                @foreach($sales->items as $item)
                    <div class="row-item">
                        <span class="name">{{ $item->productName }}</span>
                        <span class="total">Rp {{ number_format($item->totalSellingPrice,0,',','.') }}</span>
                    </div>
                    <div class="row-sub">
                        {{ $item->qty }} {{ $item->uom }} x 
                        Rp {{ number_format($item->sellingPricePerUnit,0,',','.') }}
                    </div>
                @endforeach

                <hr>

                <div class="row-total">
                    <span>Total</span>
                    <span>Rp {{ number_format($sales->totalPrice,0,',','.') }}</span>
                </div>

                <div class="row-total">
                    <span>Dibayar</span>
                    <span>Rp {{ number_format($sales->totalPayment,0,',','.') }}</span>
                </div>

                @if($sales->remainingPayment <= 0)
                <div class="row-total">
                    <span>Kembalian</span>
                    <span>Rp {{ number_format($sales->totalPayment - $sales->totalPrice,0,',','.') }}</span>
                </div>
                @else
                <div class="row-total">
                    <span>Kekurangan</span>
                    <span>-Rp {{ number_format($sales->remainingPayment,0,',','.') }}</span>
                </div>
                @endif

                <hr>

                <p class="center">Terima kasih telah berbelanja!</p>

            </div>
        </div>

        <div class="card-footer text-center no-print">
            <button onclick="window.print()" class="btn btn-primary">Print</button>
            <a href="{{ route('sales.index') }}" class="btn btn-secondary">Back</a>
        </div>

    </div>

</div>
@endsection

@section('css')
<style>

    
    .receipt {
        padding: 5px;
        font-size: 12px;
        font-family: monospace;
        width: 100%;
    }

    p{
        margin: 4px 0;
    }
    .center { text-align: center; }
    hr { border: none; border-top: 1px dashed #000; margin: 5px 0; }

    /* Item utama */
    .row-item {
        display: flex;
        justify-content: space-between;
        font-weight: bold;
        margin-bottom: 2px;
    }
    .row-item .total{
        font-weight: normal;
    }

    /* Sub info qty x harga */
    .row-sub {
        font-size: 11px;
        margin-left: 8px;
        margin-bottom: 4px;
    }

    /* Total akhir */
    .row-total {
        display: flex;
        justify-content: space-between;
        font-weight: bold;
        margin-bottom: 4px;
    }

    @media print {
        body * { visibility: hidden; }
        #print-area, #print-area * { visibility: visible; }
        .no-print { display: none !important; }
        body { margin: 0; padding: 0; }

        @page {
            size: 58mm auto;
            margin: 0;
        }
    }
</style>
@endsection