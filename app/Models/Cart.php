<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $guarded = [''];
    // 指定受保護的屬性，這裡是一個空陣列，表示所有屬性都是可賦值的。

    private $rate = 1; // 費率

    public function cartItems()
    // 返回與這個購物車關聯的多個 CartItem 實例。
    {
        return $this->hasMany(CartItem::class);
    }

    public function user()
    // 表示這個購物車屬於一個特定的使用者。
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    // 表示這個購物車對應一個訂單。
    {
        return $this->hasOne(Order::class);
    }

    public function checkout()
    // 執行結帳操作，檢查商品數量並創建訂單。
    {
        // 檢查購物車中每個項目的商品數量是否足夠
        foreach($this->cartItems as $cartItem){
            $product = $cartItem->product;
            if(!$product->checkQuantity($cartItem->quantity)) {
                return $product->title.'數量不足';
            }
        }

        $order = $this->order()->create([
            'user_id' => $this->user_id
        ]);

        // 根據用戶等級來調整費率
        if ($this->user->level == 2) { // 如果用戶等級為2
            $this->rate = 0.8; // 費率變成0.8
        }

        // 創建一個新的訂單並將訂單項目添加到訂單中
        foreach($this->cartItems as $cartItem){
            $order->orderItems()->create([
                'product_id' => $cartItem->product_id,
                'price' => $cartItem->product->price * $this->rate
            ]);
            $cartItem->product->update(['quantity' => $cartItem->product->quantity - $cartItem->quantity]);
        }
        // 新購物車的結帳狀態並返回相關的訂單
        $this->update(['checkouted' => true]);
        return $order;
    }
}
