<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'identity_number',
        'identity_type',
        'address',
        'email'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'id',
        'identity_number',
        'identity_type'
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
    public function customerContact()
    {
        return $this->hasMany(\App\Models\CustomerContact::class, 'customer_id');
    }
    public function customerMortgage()
    {
        return $this->hasMany(\App\Models\CustomerMortgage::class, 'customer_id');
    }
    public function transaction()
    {
        return $this->hasMany(\App\Models\Transaction::class, 'customer_id');
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

    /**
     * Get the user's decrypted Identity Type.
     *
     * @return string
     */
    public function getIdentityTypeAttribute()
    {
        return saEncryption($this->attributes['identity_type'], false);
    }
    /**
     * Set the user's decrypted Identity Type.
     *
     * @return string
     */
    public function setIdentityTypeAttribute($value)
    {
        $this->attributes['identity_type'] = saEncryption($value);
    }
}
