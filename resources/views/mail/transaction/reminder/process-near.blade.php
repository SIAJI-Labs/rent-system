@extends('mail.layouts.mail')

@section('mailHeader')
    <td class="esd-structure es-p15t es-p20r es-p20l" align="left">
        <table cellpadding="0" cellspacing="0" width="100%">
            <tbody>
                <tr>
                    <td width="560" class="esd-container-frame" align="center" valign="top">
                        <table cellpadding="0" cellspacing="0" width="100%">
                            <tbody>
                                <tr>
                                    <td align="center" class="esd-block-image es-p10t es-p10b" style="font-size: 0px;">
                                        <a target="_blank">
                                            <img src="{{ asset('images/undraw_online_test_gba7.png') }}" alt style="display: block;" width="100">
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" class="esd-block-text es-p20b">
                                        <h1 style="font-size: 46px; line-height: 100%;">Pemberitahuan Transaksi</h1>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </td>
@endsection

@section('mailSubHeader')
    <tr>
        <td class="esd-structure es-p10b es-p20r es-p20l" align="left">
            <table cellpadding="0" cellspacing="0" width="100%">
                <tbody>
                    <tr>
                        <td width="560" align="left" class="esd-container-frame">
                            <table cellpadding="0" cellspacing="0" width="100%">
                                <tbody>
                                    <tr>
                                        <td align="center" class="esd-block-text es-p10t es-p20b">
                                            <p>Hai {{ $data->customer->name }}, berdasarkan data yang ada di sistem kami, kamu memiliki transaksi yang sudah/akan mendekati batas tanggal sewa. Informasi transaksi dapat kamu lihat di bawah ini</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="center" class="esd-block-spacer" style="font-size:0">
                                            <table border="0" width="100%" height="100%" cellpadding="0" cellspacing="0">
                                                <tbody>
                                                    <tr>
                                                        <td style="border-bottom: 1px solid #cccccc; background:none; height:1px; width:100%; margin:0px 0px 0px 0px;"></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
        </td>
    </tr>
@endsection

@section('mailBody')
    <tr>
        <td class="esd-structure es-p20r es-p20l" align="left">
            <!--[if mso]><table width="560" cellpadding="0" cellspacing="0"><tr><td width="270" valign="top"><![endif]-->
            <table cellpadding="0" cellspacing="0" class="es-left" align="left">
                <tbody>
                    <tr>
                        <td width="270" class="es-m-p20b esd-container-frame" align="left">
                            <table cellpadding="0" cellspacing="0" width="100%">
                                <tbody>
                                    <tr>
                                        <td align="left" class="esd-block-text">
                                            <p>Invoice</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="left" class="esd-block-text">
                                            <p>Tanggal Transaksi</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="left" class="esd-block-text">
                                            <p>Periode Sewa</p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
            <!--[if mso]></td><td width="20"></td><td width="270" valign="top"><![endif]-->
            <table cellpadding="0" cellspacing="0" class="es-right" align="right">
                <tbody>
                    <tr>
                        <td width="270" align="left" class="esd-container-frame">
                            <table cellpadding="0" cellspacing="0" width="100%">
                                <tbody>
                                    <tr>
                                        <td align="left" class="esd-block-text">
                                            <p>{{ $data->invoice }}</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="left" class="esd-block-text">
                                            <p>{{ date('d F, Y / H:i:s', strtotime($data->date)) }}</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="left" class="esd-block-text">
                                            <p>{{ date('d F, Y / H:i:s', strtotime($data->start_date)) }} s/d {{ date('d F, Y / H:i:s', strtotime($data->end_date)) }}</p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
            <!--[if mso]></td></tr></table><![endif]-->
        </td>
    </tr>
    <tr>
        <td class="esd-structure es-p20r es-p20l" align="left">
            <table cellpadding="0" cellspacing="0" width="100%">
                <tbody>
                    <tr>
                        <td width="560" class="esd-container-frame" align="center" valign="top">
                            <table cellpadding="0" cellspacing="0" width="100%">
                                <tbody>
                                    <tr>
                                        <td align="center" class="esd-block-spacer es-p10t es-p10b" style="font-size:0">
                                            <table border="0" width="100%" height="100%" cellpadding="0" cellspacing="0">
                                                <tbody>
                                                    <tr>
                                                        <td style="border-bottom: 1px solid #cccccc; background:none; height:1px; width:100%; margin:0px 0px 0px 0px;"></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
        </td>
    </tr>

    @php
        $sumCalc = 0;
    @endphp
    @foreach ($data->transactionItem as $item)
        <tr>
            <td class="esd-structure es-p20r es-p20l {{ $loop->first ? '' : 'es-m-p10t' }}" align="left">
                <!--[if mso]><table width="560" cellpadding="0" cellspacing="0"><tr><td width="320" valign="top"><![endif]-->
                <table cellpadding="0" cellspacing="0" class="es-left" align="left">
                    <tbody>
                        <tr>
                            <td width="300" class="es-m-p0r es-m-p20b esd-container-frame" align="center">
                                <table cellpadding="0" cellspacing="0" width="100%">
                                    <tbody>
                                        <tr>
                                            <td align="left" class="esd-block-text">
                                                <p>{{ $item->product->name }}</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="left" class="esd-block-text">
                                                <p style="font-size: 12px;">SN: {{ $item->productDetail->serial_number }}</p>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                            <td class="es-hidden" width="20"></td>
                        </tr>
                    </tbody>
                </table>
                <!--[if mso]></td><td width="100" valign="top"><![endif]-->
                <table cellpadding="0" cellspacing="0" class="es-left" align="left">
                    <tbody>
                        <tr>
                            <td width="100" align="center" class="esd-container-frame es-m-p20b">
                                <table cellpadding="0" cellspacing="0" width="100%">
                                    <tbody>
                                        <tr>
                                            <td align="left" class="esd-block-text">
                                                <p>Harga @</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="left" class="esd-block-text">
                                                <p>Diskon</p>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <!--[if mso]></td><td width="20"></td><td width="120" valign="top"><![endif]-->
                <table cellpadding="0" cellspacing="0" class="es-right" align="right">
                    <tbody>
                        <tr>
                            <td width="120" align="left" class="esd-container-frame">
                                <table cellpadding="0" cellspacing="0" width="100%">
                                    <tbody>
                                        <tr>
                                            <td align="left" class="esd-block-text">
                                                <p>{{ formatRupiah($item->price) }}</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="left" class="esd-block-text">
                                                <p>({{ formatRupiah($item->discount) }})</p>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <!--[if mso]></td></tr></table><![endif]-->
            </td>
        </tr>

        @php
            $sumCalc += ($item->price - $item->discount);
        @endphp
    @endforeach

    <tr>
        <td class="esd-structure" align="left">
            <table cellpadding="0" cellspacing="0" width="100%">
                <tbody>
                    <tr>
                        <td width="600" class="esd-container-frame" align="center" valign="top">
                            <table cellpadding="0" cellspacing="0" width="100%">
                                <tbody>
                                    <tr>
                                        <td align="center" class="esd-block-spacer es-p5t es-p20b es-p20r es-p20l" style="font-size:0">
                                            <table border="0" width="100%" height="100%" cellpadding="0" cellspacing="0">
                                                <tbody>
                                                    <tr>
                                                        <td style="border-bottom: 1px solid #cccccc; background:none; height:1px; width:100%; margin:0px 0px 0px 0px;"></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
        </td>
    </tr>
    <tr>
        <td class="esd-structure es-p20r es-p20l" align="left">
            <!--[if mso]><table width="560" cellpadding="0" cellspacing="0"><tr><td width="320" valign="top"><![endif]-->
            <table cellpadding="0" cellspacing="0" class="es-left" align="left">
                <tbody>
                    <tr>
                        <td width="300" class="es-m-p0r es-m-p20b esd-container-frame" align="center">
                            <table cellpadding="0" cellspacing="0" width="100%">
                                <tbody>
                                    <tr>
                                        <td align="center" class="esd-empty-container" style="display: none;"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                        <td class="es-hidden" width="20"></td>
                    </tr>
                </tbody>
            </table>
            <!--[if mso]></td><td width="100" valign="top"><![endif]-->
            <table cellpadding="0" cellspacing="0" class="es-left" align="left">
                <tbody>
                    <tr>
                        <td width="100" class="es-m-p20b esd-container-frame" align="center">
                            <table cellpadding="0" cellspacing="0" width="100%">
                                <tbody>
                                    <tr>
                                        <td align="right" class="esd-block-text">
                                            <p>Jumlah</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="right" class="esd-block-text">
                                            <p>Lama Sewa</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="right" class="esd-block-text">
                                            <p><strong>Total</strong></p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
            <!--[if mso]></td><td width="20"></td><td width="120" valign="top"><![endif]-->
            <table cellpadding="0" cellspacing="0" class="es-right" align="right">
                <tbody>
                    <tr>
                        <td width="120" align="center" class="esd-container-frame">
                            <table cellpadding="0" cellspacing="0" width="100%">
                                <tbody>
                                    <tr>
                                        <td align="left" class="esd-block-text">
                                            <p>{{ formatRupiah($sumCalc) }}</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="left" class="esd-block-text">
                                            <p>{{ max(round((strtotime($item->transaction->end_date) - strtotime($item->transaction->start_date)) / (60 * 60 * 24)), 1) }} Hari</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="left" class="esd-block-text">
                                            <p><strong>{{ formatRupiah($item->transaction->amount - $item->transaction->discount) }}</strong></p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
            <!--[if mso]></td></tr></table><![endif]-->
        </td>
    </tr>

    <tr>
        <td class="esd-structure es-p15t es-p20r es-p20l" align="left">
            <table cellpadding="0" cellspacing="0" width="100%">
                <tbody>
                    <tr>
                        <td width="560" align="left" class="esd-container-frame">
                            <table cellpadding="0" cellspacing="0" width="100%">
                                <tbody>
                                    <tr>
                                        <td align="center" class="esd-block-spacer" style="font-size:0">
                                            <table border="0" width="100%" height="100%" cellpadding="0" cellspacing="0">
                                                <tbody>
                                                    <tr>
                                                        <td style="border-bottom: 1px solid #cccccc; background:none; height:1px; width:100%; margin:0px 0px 0px 0px;"></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="center" class="esd-block-text es-p10t es-p10b">
                                            <p>Jika kamu merasa sudah mengembalikan dan menyelesaikan transaksi, kamu dapat abaikan email ini. Namun jika email ini kamu terima berulang dan kamu merasa informasi di atas keliru atau tidak sesuai, mohon untuk segera hubungi kami.</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="center" class="esd-block-button">
                                            <span class="es-button-border" style="background: #023F8A;">
                                                <a href="#" class="es-button" target="_blank" style="background: #023F8A; border-color: #023F8A;"> Lihat Detail Invoice </a>
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
        </td>
    </tr>
@endsection