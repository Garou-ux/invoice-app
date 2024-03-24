<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'number',
        'customer_id',
        'date',
        'due_date',
        'reference',
        'terms_and_conditions',
        'sub_total',
        'discount',
        'total',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function invoice_items()
    {
        return $this->hasMany(InvoiceItem::class);
    }
}
