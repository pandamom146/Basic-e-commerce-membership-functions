<!-- 負責處理購物車相關的操作，包括查看購物車內容、執行結帳操作以及相關的存儲和顯示功能 -->
<!-- 使用 Eloquent ORM 來處理資料庫操作，以及使用 Laravel 提供的 auth() 函式來處理使用者身份驗證。 -->
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Cart;


class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $cart = DB::table('carts')->get()->first();
        // if (empty($cart)){
        //     DB::table('carts')->insert(["created_at" => now(), "updated_at" => now()]);
        //     $cart = DB::table('carts')->get()->first();
        // }
        // $cartItems = DB::table('cart_items')->where('cart_id', $cart->id)->get();
        // $cart = collect($cart);
        // $cart['items'] = collect($cartItems);
        $user = auth()->user();
        $cart = Cart::with(['cartItems'])->where('user_id', $user->id)
                                        // 只抓取未結帳
                                         ->where('checkouted', false) 
                                         // 如果找不到符合條件的購物車，則創建一個新的購物車。
                                         ->firstOrCreate(['user_id'=> $user->id]);
// 將購物車的內容以 JSON 格式回傳
        return response($cart);
    }

    public function checkout()
    {
        // 取得當前授權使用者的資訊
        $user = auth()->user();
        
        // 取得該使用者未結帳的購物車及其關聯的 cartItems。
        $cart = $user->carts()->where('checkouted', false)->with('cartItems')->first();
        
        // 檢查是否存在未結帳的購物車
        if($cart){
            
            // 呼叫 checkout 方法，執行結帳操作
            $result = $cart->checkout();
            return response($result);
        }
        else{
            return response('沒有購物車', 400);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
