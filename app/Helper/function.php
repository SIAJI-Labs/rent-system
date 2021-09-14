<?php

use Carbon\Carbon;
use Ramsey\Uuid\Type\Integer;
use phpDocumentor\Reflection\Types\Boolean;

/**
 * Secure String
 * 
 * @param String $string
 * @param Boolean $encrypt
 * 
 * @return String $data
 */
function saEncryption($string, $encrypt = true)
{
    $method = new \Illuminate\Encryption\Encrypter(env('ENC_KEY'), \Config::get('app.cipher'));

    $data = null;
    if($encrypt){
        $data = $method->encrypt($string);
    } else {
        try {
            $data = $method->decrypt($string);
        } catch (\RuntimeException $e) {
            $data = $string;

            \Log::debug("Check on Decryption, fail to decrypt ~ app\Helper\function@saEncryption", [
                'exception' => $e
            ]);
        }
    }

    return $data;
}

/**
 * Generate Avatar
 * 
 * @param $name = String
 * @param $type = ['male', 'female', 'human', 'identicon', 'initials', 'bottts', 'avataaars', 'jdenticon', 'gridy', 'micah']
 */
function getAvatar($name, $type = 'initials')
{
    $avatar = "https://avatars.dicebear.com/api/".$type."/".$name.".svg";
    return $avatar;
}

/**
 * Convert Number to match IDR Format
 * 
 * @param Integer $number
 * @param Boolean $prefix
 * 
 * @return String
 */
function formatRupiah($number = 0, $prefix = true)
{
    return ($prefix ? 'Rp ' : '').number_format((int)$number, 0, ',', '.');
}

/**
 * Generate Invoice
 * 
 * @return String
 */
function generateInvoice($storePrefix = null)
{
    $prefix = 'INVC';
    $date = date('dmy');
    $timestamp = (Carbon::now()->timestamp+rand(1,1000));

    return !empty($storePrefix) ? ($prefix.'/'.$date.'/'.$storePrefix.'/'.$timestamp) : ($prefix.'/'.$date.'/'.$timestamp);
}

/**
 * Generate Random String
 * 
 * @return String
 */
function generateRandomString($length = 6)
{
    $numeric = range(0, 9);
    $alpha = range('a', 'z');
    $alpha_b = range('A', 'Z');

    // Shuffle Sets
    shuffle($numeric);
    shuffle($alpha);
    shuffle($alpha_b);

    // First Shuffle (before join)
    $numeric = range(0, 9);
    $alpha = range('a', 'z');
    $alpha_b = range('A', 'Z');
    // Join Array
    $mix = implode("", $numeric).implode("", $alpha).implode("", $alpha_b);
    // Shuffle Joined Array
    $mix_shuffle = str_shuffle($mix);

    // Generate Random Character
    $string = '';
    for($i = 0; $i < $length; $i++){
        $string .= $mix_shuffle[rand(0, $length - 1)];
    }
    
    return $string;
}

/**
 * Audit Column Rename
 * 
 * @param Class $className
 * @param String $columnName
 * 
 * @return String $column
 */
function dynamicAuditColumn($className, $columnName, $oldValue, $newValue)
{
    $column = null;
    // Available Class Name
    $transaction = new \App\Models\Transaction();
    $transactionLog = new \App\Models\TransactionLog();
    $transactionItem = new \App\Models\TransactionItem();
    // Get Available Class Name as String
    $transaction = get_class($transaction);
    $transactionLog = get_class($transactionLog);
    $transactionItem = get_class($transactionItem);

    switch($className){
        case $transaction:
            // Transaction Audit
            switch($columnName){
                case 'user_id':
                    $oldValue = \App\Models\User::find($oldValue) ? \App\Models\User::find($oldValue)->name : null;
                    $newValue = \App\Models\User::find($newValue) ? \App\Models\User::find($newValue)->name : null;
                    $column = 'Kasir';
                    break;
                case 'store_id':
                    $oldValue = \App\Models\Store::find($oldValue) ? \App\Models\Store::find($oldValue)->name : null;
                    $newValue = \App\Models\Store::find($newValue) ? \App\Models\Store::find($newValue)->name : null;
                    $column = 'Toko';
                    break;
                case 'customer_id':
                    $oldValue = \App\Models\Customer::find($oldValue) ? \App\Models\Customer::find($oldValue)->name : null;
                    $newValue = \App\Models\Customer::find($newValue) ? \App\Models\Customer::find($newValue)->name : null;
                    $column = 'Kostumer';
                    break;
                case 'invoice':
                    $column = 'Invoice';
                    break;
                case 'date':
                    $column = 'Tanggal Transaksi';
                    break;
                case 'start_date':
                    $oldValue = date("d F, Y / H:i:s", strtotime($oldValue));
                    $newValue = date("d F, Y / H:i:s", strtotime($newValue));
                    $column = 'Tanggal Sewa (Mulai)';
                    break;
                case 'end_date':
                    $oldValue = date("d F, Y / H:i:s", strtotime($oldValue));
                    $newValue = date("d F, Y / H:i:s", strtotime($newValue));
                    $column = 'Tanggal Sewa (Kembali)';
                    break;
                case 'must_end_date':
                    $oldValue = date("d F, Y / H:i:s", strtotime($oldValue));
                    $newValue = date("d F, Y / H:i:s", strtotime($newValue));
                    $column = 'Tanggal Sewa (Harus Kembali)';
                    break;
                case 'back_date':
                    $oldValue = date("d F, Y / H:i:s", strtotime($oldValue));
                    $newValue = date("d F, Y / H:i:s", strtotime($newValue));
                    $column = 'Tanggal Kembali';
                    break;
                case 'amount':
                    $oldValue = formatRupiah(($oldValue));
                    $newValue = formatRupiah(($newValue));
                    $column = 'Jumlah Biaya';
                    break;
                case 'discount':
                    $oldValue = formatRupiah(($oldValue));
                    $newValue = formatRupiah(($newValue));
                    $column = 'Jumlah Diskon';
                    break;
                case 'paid':
                    $oldValue = formatRupiah(($oldValue));
                    $newValue = formatRupiah(($newValue));
                    $column = 'Jumlah Dibayar';
                    break;
                case 'charge':
                    $oldValue = formatRupiah(($oldValue));
                    $newValue = formatRupiah(($newValue));
                    $column = 'Biaya Denda';
                    break;
                case 'extra':
                    $oldValue = formatRupiah(($oldValue));
                    $newValue = formatRupiah(($newValue));
                    $column = 'Biaya Tambahan';
                    break;
                case 'status':
                    $oldValue = ucwords(($oldValue));
                    $newValue = ucwords(($newValue));
                    $column = 'Status Transaksi';
                    break;
                case 'note':
                    $column = 'Catatan Transaksi';
                    break;
            }
            break;
        case $transactionItem:
            // Transaction Item Audit
            switch($columnName){
                case 'transaction_id':
                    $oldValue = \App\Models\Transaction::find($oldValue) ? \App\Models\Transaction::find($oldValue)->invoice : null;
                    $newValue = \App\Models\Transaction::find($newValue) ? \App\Models\Transaction::find($newValue)->invoice : null;
                    $column = 'Invoice Transaksi';
                    break;
                case 'product_id':
                    $oldValue = \App\Models\Product::find($oldValue) ? \App\Models\Product::find($oldValue)->name : null;
                    $newValue = \App\Models\Product::find($newValue) ? \App\Models\Product::find($newValue)->name : null;
                    $column = 'Produk';
                    break;
                case 'product_detail_id':
                    $oldValue = \App\Models\ProductDetail::find($oldValue) ? \App\Models\ProductDetail::find($oldValue)->serial_number : null;
                    $newValue = \App\Models\ProductDetail::find($newValue) ? \App\Models\ProductDetail::find($newValue)->serial_number : null;
                    $column = 'Serial Number';
                    break;
                case 'price':
                    $oldValue = formatRupiah(($oldValue));
                    $newValue = formatRupiah(($newValue));
                    $column = 'Biaya @';
                    break;
                case 'discount':
                    $oldValue = formatRupiah(($oldValue));
                    $newValue = formatRupiah(($newValue));
                    $column = 'Potongan @';
                    break;
                case 'note':
                    $column = 'Catatan';
                    break;
            }
            break;
    }

    return [
        'column' => $column,
        'old' => $oldValue,
        'new' => $newValue
    ];
}

/**
 * Convert HEX to RGB / RGBA
 * 
 */
function convertHexToRgb($hex, $alpha = null){
    $hex      = str_replace('#', '', $hex);
    $length   = strlen($hex);
    $rgb['r'] = hexdec($length == 6 ? substr($hex, 0, 2) : ($length == 3 ? str_repeat(substr($hex, 0, 1), 2) : 0));
    $rgb['g'] = hexdec($length == 6 ? substr($hex, 2, 2) : ($length == 3 ? str_repeat(substr($hex, 1, 1), 2) : 0));
    $rgb['b'] = hexdec($length == 6 ? substr($hex, 4, 2) : ($length == 3 ? str_repeat(substr($hex, 2, 1), 2) : 0));

    if ($alpha) {
        $rgb['a'] = $alpha;
    }

    return implode(", ", $rgb);
}

// Indonesian Format (Month)
function formatBulan($angka){
    $name = '';
    switch($angka){
        case 1:
            $name = 'Januari';
            break;
        case 2:
            $name = 'Februari';
            break;
        case 3:
            $name = 'Maret';
            break;
        case 4:
            $name = 'April';
            break;
        case 5:
            $name = 'Mei';
            break;
        case 6:
            $name = 'Juni';
            break;
        case 7:
            $name = 'Juli';
            break;
        case 8:
            $name = 'Agustus';
            break;
        case 9:
            $name = 'September';
            break;
        case 10:
            $name = 'Oktober';
            break;
        case 11:
            $name = 'November';
            break;
        case 12:
            $name = 'Desember';
            break;
    }

    return $name;
}