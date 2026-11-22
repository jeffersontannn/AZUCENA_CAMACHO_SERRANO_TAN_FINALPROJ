<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class Sale extends Model
{
    public $timestamps = false;

    protected $fillable = ['product_id', 'customer_id', 'sold_by', 'quantity', 'total_price', 'created_at'];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sold_by');
    }

    /**
     * All sales joined with product, customer, and staff names.
     */
    public static function history()
    {
        return DB::table('sales as s')
            ->select([
                's.id',
                'p.name as product_name',
                'c.full_name as customer_name',
                'u.full_name as staff_name',
                's.quantity',
                's.total_price',
                's.created_at',
            ])
            ->leftJoin('products as p', 'p.id', '=', 's.product_id')
            ->leftJoin('customers as c', 'c.id', '=', 's.customer_id')
            ->leftJoin('users as u', 'u.id', '=', 's.sold_by')
            ->orderBy('s.created_at', 'DESC')
            ->get();
    }
}
