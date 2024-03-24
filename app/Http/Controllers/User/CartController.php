<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Item;
use App\Models\Stock;
use App\Models\Cart;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Constants\Common;


class CartController extends Controller
{
  //

  public function index()
  {
    $user = User::findOrFail(Auth::id());
    //中間テーブルはwithとかで持ってこなくても、A.pivot.columnで取得できる。
    // $products = $user->products;
    // 上の例にwithをつけるとこうなるらしい。
    // $user->productsは、リレーションのロードではなく、ユーザーに関連付けられた商品のコレクションを返します。これはEloquentの動的プロパティです。
    // $user->products()は、productsリレーションをロードするEloquentクエリビルダを返します。これにより、追加のクエリ条件を指定したり、関連するモデルをロードしたりできます。
    $products = $user->products()->with('imageFirst')->get();
    $totalPrice = 0;

    foreach ($products as $product) {
      //中間テーブルはpivotで経由
      $totalPrice += $product->price * $product->pivot->quantity;
    }
    return Inertia::render('User/Cart/Index', [
      'products' => $products, 'totalPrice' => $totalPrice
    ]);
  }
  public function add(Request $request)
  {
    $itemInCart = Cart::where('user_id', Auth::id())->where('product_id', $request->product_id)->first();
    if ($itemInCart) {
      $itemInCart->quantity += $request->quantity;
      $itemInCart->save();
    } else {
      Cart::create([
        'user_id' => Auth::id(),
        'product_id' => $request->product_id,
        'quantity' => $request->quantity,
      ]);
    }
    return redirect()->route('user.cart.index')->with([
      'message' => ' カートに商品を保存しました',
      'status' => 'message'
    ]);
  }

  public function checkout(Request $request)
  {

    $user = User::findOrFail(Auth::id());
    $products = $user->products()->with('imageFirst')->get();
    $lineItems = [];

    //Udemyは情報が少し古いので、こちらのURLを参照する
    //https://docs.stripe.com/payments/accept-a-payment?platform=web&ui=stripe-hosted#create-product-prices-upfront

    // lineItemsのデータは以下のサイトから
    // https://docs.stripe.com/payments/checkout/migrating-prices
    foreach ($products as $product) {
      $quantity = 0;
      $quantity = Stock::where('product_id', $product->id)->sum('quantity');

      if ($product->pivot->quantity > $quantity) {
        return redirect()->route('user.cart.index')->with([
          'message' => '在庫不足です。注文数を減らしてください。現在の在庫：' . $product->pivot->quantity,
          'status' => 'alert'
        ]);
      } else {
        $lineItem = [
          'price_data' => [
            'currency' => 'jpy',
            'unit_amount' => $product->price,
            'product_data' => [
              'name' => $product->name,
              'description' => $product->information,
            ],
          ],
          'quantity' => $product->pivot->quantity,
        ];
        array_push($lineItems, $lineItem);
        Stock::create([
          'product_id' => $product->id,
          'type' => Common::PRODUCT_LIST['reduce'],
          'quantity' => $product->pivot->quantity *= -1,
        ]);
      }

      // $stripeをつくり、チェーンしていくイメージ

    }

    \Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
    $session = \Stripe\Checkout\Session::create([
      'mode' => 'payment',
      'line_items' => $lineItems,
      'success_url' => route('user.cart.success'),
      'cancel_url' => route('user.cart.cancel'),
    ]);

    $publicKey = env('STRIPE_PUBLIC_KEY');

    return Inertia::render('User/Cart/Checkout', [
      'session' => $session, 'publicKey' => $publicKey,
    ]);
  }

  public function success()
  {
    $prodcuts = Cart::where('user_id', Auth::id())->delete();
    return redirect()->route('user.cart.index')->with([
      'message' => '決済が成功しました',
      'status' => 'message'
    ]);;
  }

  public function cancel()
  {
    //userはcartの値を持っていて、カートは決済が終わったら在庫を減らすようにしている。キャンセル時はカートには残っている
    $user = User::findOrFail(Auth::id());
    foreach ($user->products as $product) {
      Stock::create([
        'product_id' => $product->id,
        'type' => Common::PRODUCT_LIST['add'],
        'quantity' => $product->pivot->quantity,
      ]);
    }
    return redirect()->route('user.cart.index')->with([
      'message' => '決済はキャンセルされました',
      'status' => 'alert'
    ]);;
  }

  public function delete(Request $request)
  {
    Cart::findOrFail($request->cartid)->delete();
    return redirect()->route('user.cart.index')->with([
      'message' => $request->productname . 'をカートから除外しました',
      'status' => 'alert'
    ]);
  }
}
