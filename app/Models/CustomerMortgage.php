<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerMortgage extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'customer_id',
        'type',
        'value',
        'pict'
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

    /**
     * Get the user's decrypted Value.
     *
     * @return string
     */
    public function getValueAttribute()
    {
        return saEncryption($this->attributes['value'], false);
    }
    /**
     * Get the user's decrypted Value.
     *
     * @return string
     */
    public function getPictAttribute()
    {
        return saEncryption($this->attributes['pict'], false);
    }

    /**
     * Set the user's decrypted Value.
     *
     * @return string
     */
    public function setValueAttribute($value)
    {
        $this->attributes['value'] = saEncryption($value);
    }
    /**
     * Set the user's decrypted Pict.
     *
     * @return string
     */
    public function setPictAttribute($value)
    {
        $this->attributes['pict'] = !empty($value) ? saEncryption($value) : null;
    }
}
