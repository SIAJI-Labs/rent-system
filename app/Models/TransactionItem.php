<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use OwenIt\Auditing\Contracts\Auditable;

class TransactionItem extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    /**
     * Attributes to exclude from the Audit.
     *
     * @var array
     */
    protected $auditExclude = [
        'uuid',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'transaction_id',
        'product_id',
        'product_detail_id',
        'price',
        'discount',
        'note'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'id'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        // 
    ];
    
    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'uuid';
    }

    /**
     * Primary Key Relation
     * 
     * @return model
     */

    /**
     * Foreign Key Relation
     * 
     * @return model
     */
    public function transaction()
    {
        return $this->belongsTo(\App\Models\Transaction::class, 'transaction_id');
    }
    public function product()
    {
        return $this->belongsTo(\App\Models\Product::class, 'product_id');
    }
    public function productDetail()
    {
        return $this->belongsTo(\App\Models\ProductDetail::class, 'product_detail_id');
    }

    /**
     * The "boot" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        // Listen to Create Event
        static::creating(function ($model) {
            // Always generate UUID on Data Create
            $model->{'uuid'} = (string) \Str::uuid();
        });
    }
}
