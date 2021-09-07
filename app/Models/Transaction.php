<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use OwenIt\Auditing\Contracts\Auditable;

class Transaction extends Model implements Auditable
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
     * {@inheritdoc}
     */
    public function transformAudit(array $data): array
    {
        return $data;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'store_id',
        'customer_id',
        'invoice',
        'date',
        'start_date',
        'end_date',
        'must_end_date',
        'back_date',
        'amount',
        'discount',
        'paid',
        'charge',
        'extra',
        'status',
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
    public function transactionLog()
    {
        return $this->hasMany(\App\Models\TransactionLog::class, 'transaction_id');
    }
    public function transactionItem()
    {
        return $this->hasMany(\App\Models\TransactionItem::class, 'transaction_id');
    }
    public function accounting()
    {
        return $this->hasMany(\App\Models\Accounting::class, 'transaction_id');
    }

    /**
     * Foreign Key Relation
     * 
     * @return model
     */
    public function user()
    {
        return $this->belongsTo(\App\models\User::class, 'user_id');
    }
    public function store()
    {
        return $this->belongsTo(\App\Models\Store::class, 'store_id');
    }
    public function customer()
    {
        return $this->belongsTo(\App\Models\Customer::class, 'customer_id');
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
