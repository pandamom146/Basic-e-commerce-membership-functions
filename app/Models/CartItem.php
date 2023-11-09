<!-- 定義與 Product 和 Cart 模型的關聯。
實現一個附加屬性 current_price，用於計算並返回當前商品的價格。 -->
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CartItem extends Model
{
    use HasFactory;
    use SoftDeletes;  // 使用軟刪除特性

    protected $filable = []; // 白名單(只能更改)
    protected $guarded = []; // 黑名單(不能更改)
    protected $hidden = []; //隱藏欄位 ，表示沒有任何屬性會被隱藏。
    protected $appends = ['current_price'];// 表示這個模型在序列化時會包含一個名為 current_price 的屬性。

    public function getCurrentPriceAttribute()
    // 返回一個計算得到的 current_price 屬性值
    {
        return $this->quantity * 10;
    }

    public function product()
    // 表示這個購物車項目對應一個特定的產品。
    {
        return $this->belongsTo(Product::class);
    }

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }
}
