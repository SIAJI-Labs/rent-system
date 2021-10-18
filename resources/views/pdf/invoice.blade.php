@extends('layouts.app-pdf', [
])

@section('css_inline')
    <style>
        body {
            background-color: rgba(243,244,246,1) !important;
        }
        .wrapper {
            width: 75%;
            margin: 5rem auto;
            background: #fff;
            padding: 1rem;
        }
        .wrapper .container {
            
        }
        .wrapper .container .content {

        }
        .wrapper .container .content .pdf-wrapper {

        }
        .wrapper .container .content .pdf-wrapper .header {
            text-align: center
        }
        .wrapper .container .content .pdf-wrapper .header .title {
            margin: 0;
            margin-bottom: .5rem;
            text-align: center;
            font-size: 1.9rem
        }
        .wrapper .container .content .pdf-wrapper .header .description {
            text-align: center;
        }
        .wrapper .container .content .pdf-wrapper .table-data {
            width: 100%;
            margin-top: 2.5rem
        }
    </style>
@endsection

@section('content')
    <div class="pdf-wrapper">
        <div class="header">
            <h1 class="title">Invoice Transaksi</h1>
            <span class="description">Terimaksih telah mempercayakan kami sebagai pihak persewaan pilihan anda. Berikut ini adalah ringkasan transaksi anda:</span>
        </div>

        {{-- Store and CUst Data --}}
        <table class="table-data">
            <tr>
                <th width="50%" style="border-bottom: 1px solid #000;padding-bottom: 1rem;" align="left">
                    <h1 style="margin: 0;font-size:1rem;text-align:left">Data Toko</h1>
                </th>
                <th width="50%" style="border-bottom: 1px solid #000;padding-bottom: 1rem;" align="left">
                    <h1 style="margin: 0;font-size:1rem;text-align:left">Data Kostumer</h1>
                </th>
            </tr>

            <tr>
                <td style="vertical-align: baseline;">
                    <table>
                        <tr>
                            <th align="left" style="font-size:.8rem;">Invoice</th>
                            <td align="left" style="font-size:.8rem;">#{{ $data->invoice }}</td>
                        </tr>
                        <tr>
                            <th align="left" style="font-size:.8rem;">Kasir</th>
                            <td align="left" style="font-size:.8rem;">{{ $data->user->name }}</td>
                        </tr>
                        <tr>
                            <th  align="left" style="font-size:.8rem;">Toko</th>
                            <td align="left" style="font-size:.8rem;">{{ $data->store->name  }}</td>
                        </tr>
                        <tr>
                            <th  align="left" style="font-size:.8rem;">Telp</th>
                            <td align="left" style="font-size:.8rem;">{{ $data->store->phone ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th  align="left" style="font-size:.8rem;">Alamat</th>
                            <td align="left" style="font-size:.8rem;">{{ $data->store->address ?? '-' }}</td>
                        </tr>
                    </table>
                </td>
                <td style="vertical-align: baseline;">
                    <table>
                        <tr>
                            <th  align="left" style="font-size:.8rem;">Nama</th>
                            <td  align="left" style="font-size:.8rem;">{{ $data->customer->name }}</td>
                        </tr>
                        <tr>
                            <th  align="left" style="font-size:.8rem;">Email</th>
                            <td  align="left" style="font-size:.8rem;">{{ $data->customer->email ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th  align="left" style="font-size:.8rem;">Alamat</th>
                            <td  align="left" style="font-size:.8rem;">{{ $data->customer->address ?? '-' }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        {{-- Data Transaksi --}}
        <table class="table-data" style="margin-top: 1rem">
            <tr>
                <th width="100%" style="border-bottom: 1px solid #000;padding-bottom: 1rem;" align="left">
                    <h1 style="margin: 0;font-size:1rem;text-align:left">Data Transaksi</h1>
                </th>
            </tr>
            <tr>
                <td style="vertical-align: baseline;">
                    <table class="tw__text-left">
                        <tr>
                            <th  align="left" style="font-size:.8rem;padding-right: .5rem;">Status</th>
                            <td  align="left" style="font-size:.8rem;">{{ ucwords($data->status) }}</td>
                        </tr>
                        <tr>
                            <th  align="left" style="font-size:.8rem;padding-right: .5rem;">Tanggal Transaksi</th>
                            <td  align="left" style="font-size:.8rem;">{{ date("d M, Y / H:i", strtotime($data->date)) }}</td>
                        </tr>
                        <tr>
                            <th  align="left" style="font-size:.8rem;padding-right: .5rem;">Periode Sewa</th>
                            <td  align="left" style="font-size:.8rem;">{{ date("d M, Y / H:i", strtotime($data->start_date)).' - '.date("d M, Y / H:i", strtotime($data->end_date)) }}</td>
                        </tr>
                        <tr>
                            <th  align="left" style="font-size:.8rem;padding-right: .5rem;">Catatan</th>
                            <td  align="left" style="font-size:.8rem;">{{ $data->note ?? '-' }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <table class="table-data" style="margin-top: 1.5rem">
            <tr>
                <th style="font-size: 1rem;" align="left">#</th>
                <th style="font-size: 1rem;" align="left">Produk</th>
                <th style="font-size: 1rem;" align="left">Serial Number</th>
                <th style="font-size: 1rem;" align="left">Biaya</th>
            </tr>
            @php
                $sum = 0;
            @endphp
            @foreach ($data->transactionItem as $key => $item)
                <tr>
                    <td   align="left" style="font-size:.8rem;">
                        {{ $key + 1 }}
                    </td>
                    <td  align="left" style="font-size:.8rem;">
                        <span style="border-bottom: 1px solid rgba(0,0,0,.4)">{{ $item->product->name }}</span><br/>
                        <small style="display: block">Catatan: {{ $item->note ?? '-' }}</small>
                    </td>
                    <td  align="left" style="font-size:.8rem;">
                        <span>{{ $item->productDetail->serial_number }}</span>
                    </td>
                    <td  align="left" style="font-size:.8rem;">
                        <div>
                            <span>@</span>
                            <span>{{ formatRupiah($item->price) }}</span>
                        </div>
                        <div>
                            <span>Diskon</span>
                            <span>{{ formatRupiah($item->discount) }}</span>
                        </div>
                    </td>
                </tr>
                @php
                    $sum += ($item->price - $item->discount);
                @endphp
            @endforeach
            <tr>
                <th  align="right" style="font-size:.8rem;padding-top: 1rem" colspan="3">Jumlah</th>
                <td   align="left" style="font-size:.8rem;padding-top: 1rem">{{ formatRupiah($sum) }}</td>
            </tr>
            <tr>
                <th   align="right" style="font-size:.8rem;" colspan="3">Lama Sewa</th>
                <td   align="left" style="font-size:.8rem;">{{ formatRupiah(round((strtotime($data->end_date) - strtotime($data->start_date)) / (60 * 60 * 24)), '') }} hari</td>
            </tr>
            <tr>
                <th   align="right" style="font-size:.8rem;" colspan="3">Total</th>
                <td   align="left" style="font-size:.8rem;"><strong>{{ formatRupiah($data->amount - $data->discount) }}</strong></td>
            </tr>
            <tr>
                <th   align="right" style="font-size:.8rem;" colspan="3">Dibayar</th>
                <td   align="left" style="font-size:.8rem;"><strong>{{ formatRupiah($data->paid) }}</strong></td>
            </tr>
            <tr>
                <th   align="right" style="font-size:.8rem;" colspan="3">Kekurangan</th>
                <td   align="left" style="font-size:.8rem;"><strong>{{ ($data->amount - $data->discount) - $data->paid > 0 ? formatRupiah(($data->amount - $data->discount) - $data->paid) : '-' }}</strong></td>
            </tr>
        </table>
    </div>
@endsection