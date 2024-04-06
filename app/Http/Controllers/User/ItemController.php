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
use Illuminate\Support\Facades\Mail;
use App\Mail\TestMail;
use App\Jobs\sendThanksMail;

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

    // クエリパラメータを取得
    $sort = $request->query('sort') ?? 'recommend';
    $pagination = $request->query('pagination') ?? '25';
    $searchword = $request->query('searchword') ?? '';
    $secondary = $request->query('secondary') ?? '0';

    $categories = PrimaryCategory::with('secondary')->get();

    // dd($request);
    // $querySort  = Common::SORT_ORDER[$sort];

    //電子書籍のようなデータでもquantityを1にする。面倒だったらscopeの設定を変える。

    $products = Product::availableItems()
      ->selectCategory($secondary)
      ->searchKeyword($searchword)
      ->sortOrder($sort)
      ->paginate($pagination);

    //非同期で送信
    // sendThanksMail::dispatch();

    return Inertia::render('User/Items/Index', [
      'products' => $products,
      'sort' => $sort,
      'pagination' => $pagination,
      'searchword' => $searchword,
      'categories' => $categories,
      'selectedSecondary' => $secondary,
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
