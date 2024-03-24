<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Stock;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Product;
use PhpParser\Node\Stmt\Foreach_;
use App\Models\PrimaryCategory;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ItemController extends Controller
{
  public function __construct()
  {
    //middlewareはレンダリング前に機能するメソッド
    $this->middleware('auth:users');
  }
  //
  public function index()
  {
    //電子書籍のようなデータでもquantityを1にする。面倒だったら下の設定を変える。
    $stocks = DB::table('t_stocks')
      ->select('product_id', DB::raw('sum(quantity) as quantity'))->groupBy('product_id')->having('quantity', '>=', 1);

    $productsProto = DB::table('products')->joinSub($stocks, 'stock', function ($join) {
      $join->on('products.id', '=', 'stock.product_id');
    })
      ->join('shops', 'products.shop_id', '=', 'shops.id')
      ->where('shops.is_selling', true)
      ->where('products.is_selling', true)
      ->get();

    //クエリビルダでやる場合は下のを足す。今回はidだけわかればいいので、idからproductsを作り直す。
    // ->join('secondary_categories', 'products.secondary_category_id', '=', 'secondary_categories.id')
    // ->join('images as image1', 'products.image1', '=', 'image1.id')
    //  略 (前ページのwhere句)
    // ->select('products.id as id', 'products.name as name', 'products.price' ,'products.sort_order as sort_order'
    // ,'products.information', 'secondary_categories.name as category' ,'image1.filename as filename')
    // ->get();

    //vueだとwithをつかわないとリレーションがもってこれないので、同じIDを持つもう一つのコレクションを取得します。
    $productIds = $productsProto->pluck('product_id')->toArray();

    $products = Product::whereIn('id', $productIds)
      ->with('imageFirst', 'category.primary')
      ->get();
    return Inertia::render('User/Items/Index', [
      'products' => $products
    ]);
  }

  public function show($id)
  {
    // withはリレーションすべてを取得するので、findorfailやwhereはwithのあとで行う。
    $product = Product::with('imageFirst', 'imageSecond', 'imageThird', 'imageFourth', 'category.primary', 'shop')
      ->findOrFail($id);
    $quantity = Stock::where('product_id', $product->id)->sum('quantity');
    $quantity = intval($quantity);
    return Inertia::render('User/Items/Show', [
      'product' => $product, 'quantity' => $quantity,
    ]);
  }
}
