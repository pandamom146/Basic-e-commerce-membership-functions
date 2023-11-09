<!-- 負責處理與產品相關的資料庫操作，並提供一個用於檢查庫存量的方法 -->
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $guarded = [''];

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function checkQuantity($quantity){
        if ($this->quantity < $quantity) {
            return false;
        }
        return true;
    }
}
