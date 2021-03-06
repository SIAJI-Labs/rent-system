<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'phone',
        'address',
        'note',
        'longitude',
        'latitude',
        'invoice_prefix',
        'chart_hex_color',
        'chart_rgb_color',
        'is_active'
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
        'is_active' => 'boolean'
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
    public function staff()
    {
        return $this->hasMany(\App\Models\Staff::class, 'store_id');
    }
    public function productDetail()
    {
        return $this->hasMany(\App\Models\ProductDetail::class, 'store_id');
    }
    public function transaction()
    {
        return $this->hasMany(\App\Models\Transaction::class, 'store_id');
    }
    public function accounting()
    {
        return $this->hasMany(\App\Models\Accounting::class, 'store_id');
    }

    /**
     * Foreign Key Relation
     * 
     * @return model
     */

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
