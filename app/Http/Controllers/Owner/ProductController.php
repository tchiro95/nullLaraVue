<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Image;
use App\Models\PrimaryCategory;
use App\Models\Owner;
use App\Models\Shop;
use App\Models\Stock;
use Illuminate\Validation\Rules;
use Throwable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\productRequest;
use App\Constants\Common;

class ProductController extends Controller
{
  public function __construct()
  {
    //middlewareはレンダリング前に機能するメソッド
    $this->middleware('auth:owners');

    //parameterを手打ちした際に他の店でログインできないようにするカスタムmiddleware
    $this->middleware(function ($request, $next) {
      //route()->parameter()で取れるurlのidとauth::idでとれるログインIDを比べる。
      //routeのidは文字列、authは数字なのでフォーマットする

      $id = $request->route()->parameter('product');

      if (!is_null($id)) {
        $productsOwnerID = Product::findOrFail($id)->shop->owner->id;

        $productID = (int)$productsOwnerID;
        if ($productID !== Auth::id()) {
          abort(404);
        }
      }

      return $next($request);
    });
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {

    // withはeagerloadでn+1を解決
    // ネストしたリレーションの場合、aaa.bbb.のようにwithの中に書いていく。
    $ownerInfo = Owner::with('shop.product.imageFirst')->where('id', Auth::id())->get();
    //ownerInfoでtemplateでループするとvueだとうまくいかないので、productsは別に持ってくる。collection型はforeachでないとアクセスできない。
    foreach ($ownerInfo as $owner) {
      $products = $owner->shop->product;
    }
    return Inertia::render('Owner/Products/Index', [
      'ownerInfo' => $ownerInfo,
      'products' => $products,
    ]);

    //クエリビルダでやる場合は下のを足す。produtのモデルにスコープを書いてあるので、それを使ってください。
    // $products = Product::availableItems()->get()

  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    $shops = Shop::where('owner_id', Auth::id())->select('id', 'name')->get();
    $images = Image::where('owner_id', Auth::id())->select('id', 'title', 'filename')->get();
    $categories = PrimaryCategory::with('secondary')->get();

    return Inertia::render('Owner/Products/Create', ['shops' => $shops, 'images' => $images, 'categories' => $categories]);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(productRequest $request)
  {
    //
    try {
      //closureの中で変数を使う場合、use($value)で使える。
      DB::transaction(function () use ($request) {
        $product = Product::create([
          'shop_id' => $request->shop_id,
          'name' => $request->name,
          'information' => $request->information,
          'price' => $request->price,
          'is_selling' => $request->is_selling,
          'sort_order' => $request->sort_order,
          'secondary_category_id' => $request->category,
          'image1' => $request->image1,
          'image2' => $request->image2,
          'image3' => $request->image3,
          'image4' => $request->image4,
        ]);

        Stock::create([
          'product_id' => $product->id,
          'type' => 1,
          'quantity' => $request->quantity,
        ]);
      }, 3);
    } catch (Throwable $e) {
      //エラーがでたら例外処理
      Log::error($e);
      throw $e;
    }
    return to_route('owner.products.index')->with([
      'message' => 'アイテムを登録しました',
      'status' => 'message'
    ]);
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
    $product = Product::findOrfail($id);
    $quantity = Stock::where('product_id', $product->id)->sum('quantity');

    $shops = Shop::where('owner_id', Auth::id())->select('id', 'name')->get();
    $images = Image::where('owner_id', Auth::id())->select('id', 'title', 'filename')->get();
    $categories = PrimaryCategory::with('secondary')->get();

    return Inertia::render('Owner/Products/Edit', ['product' => $product, 'quantity' => $quantity, 'shops' => $shops, 'images' => $images, 'categories' => $categories]);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(productRequest $request, $id)
  {
    //
    $request->validate([
      'current_quantity' => 'nullable|integer',
    ]);

    $product = Product::findOrFail($id);
    $quantity = Stock::where('product_id', $product->id)->sum('quantity');
    if ($request->current_quantity !== $quantity) {
      $route_id = $request->route()->parameter('product');
      return redirect()->route('owner.products.edit', ['product' => $route_id])->with([
        'message' => '在庫数が変化しています。リロードしてから再度更新作業を行ってください',
        'status' => 'alert'
      ]);
    } else {
      try {
        //closureの中で変数を使う場合、use($value)で使える。
        DB::transaction(function () use ($request, $product) {
          $product->name = $request->name;
          $product->information = $request->information;
          $product->price = $request->price;
          $product->is_selling = $request->is_selling;
          $product->sort_order = $request->sort_order;
          $product->secondary_category_id = $request->category;
          $product->image1 = $request->image1;
          $product->image2 = $request->image2;
          $product->image3 = $request->image3;
          $product->image4 = $request->image4;
          $product->save();

          if ($request->stockType == Common::PRODUCT_LIST['add']) {
            $newQuantity = $request->quantity;
          }
          if ($request->stockType == Common::PRODUCT_LIST['reduce']) {
            $newQuantity = $request->quantity * -1;
          }

          Stock::create([
            'product_id' => $product->id,
            'type' => $request->stockType,
            'quantity' => $newQuantity,
          ]);
        }, 3);
      } catch (Throwable $e) {
        //エラーがでたら例外処理
        Log::error($e);
        throw $e;
      }
      return to_route('owner.products.index')->with([
        'message' => 'アイテムを更新しました',
        'status' => 'message'
      ]);
    }
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
    $product = Product::findOrFail($id)->delete();

    return to_route('owner.products.index')->with([
      'message' => '教材を削除しました',
      'status' => 'alert'
    ]);
  }
}
