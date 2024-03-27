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
use App\Constants\Common;

class ItemController extends Controller
{
  public function __construct()
  {
    //middlewareはレンダリング前に機能するメソッド
    $this->middleware('auth:users');    //parameterを手打ちした際に他の店でログインできないようにするカスタムmiddleware
    $this->middleware(function ($request, $next) {
      $id = $request->route()->parameter('item');
      if (!is_null($id)) {
        $isItemId = Product::availableItems()->where('products.id', $id)->exists();
        if (!$isItemId) {
          abort(404);
        }
      }
      return $next($request);
    });


    //販売停止の商品にアクセスしても404を返す

  }
  //
  public function index(Request $request)
  {
    $request_order = ($request->sort == null) ? '0' : $request->sort;
    $pagination = ($request->pagination == null) ? '50' : $request->pagination;

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

    //vueだとwithをつかわないとリレーションがもってこれないので、同じIDを持つもう一つのコレクションを取得します。
    $productIds = $productsProto->pluck('product_id')->toArray();

    $products = Product::whereIn('id', $productIds)
      ->with('imageFirst', 'category.primary')->sortOrder($request->sort)->paginate($pagination);


    $constant_sortorder = Common::SORT_ORDER;

    return Inertia::render('User/Items/Index', [
      'products' => $products,
      'constant_sortorder' => $constant_sortorder,
      'request_order' => $request_order,
      'pagination' => $pagination,
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
