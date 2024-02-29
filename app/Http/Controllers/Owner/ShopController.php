<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Owner;
use App\Models\Shop;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ShopController extends Controller
{
  //__constructは作成時に呼ばれるメソッド。その際に認証をしている
  public function __construct()
  {
    //middlewareはレンダリング前に機能するメソッド
    $this->middleware('auth:owners');

    //parameterを手打ちした際に他の店でログインできないようにするカスタムmiddleware
    $this->middleware(function ($request, $next) {
      //route()->parameter()で取れるurlのidとauth::idでとれるログインIDを比べる。
      //routeのidは文字列、authは数字なのでフォーマットする
      $id = $request->route()->parameter('shop');
      if (!is_null($id)) {
        $shopsOwnerID = Shop::findOrFail($id)->owner->id;
        $shopID = (int)$shopsOwnerID;
        $ownerID = Auth::id();
        if ($shopID !== $ownerID) {
          return view(404);
        }
      }
      return $next($request);
    });
  }

  public function index()
  {
    $shops = Shop::where('owner_id', Auth::id())->get();

    return Inertia::render('Owner/Shops/Index', [
      'shops' => $shops,
    ]);
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    //リレーションのデータを持っていく場合は、withを使う
    $shop = Shop::with('owner')->findOrFail($id);
    return Inertia::render('Owner/Shops/Edit', [
      'shop' => $shop,
    ]);
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
    $request->validate([
      'name' => 'required|string|max:30',
      'information' => 'required|string|max:255',
    ]);

    //storageにファイルをアップする。
    //マニュアルのより深く知る→ファイルストレージ参照
    //
    $imageFile = $request->image; //一時保存
    if (!is_null($imageFile) && $imageFile->isValid()) {
      //putfileはファイル名を自動でつけてくれる
      Storage::putFile('public/shops', $imageFile);
    }

    return to_route('owner.shops.index')->with([
      'message' => '更新しました',
      'status' => 'message'
    ]);

    $shop = Shop::findOrFail($id);
    $shop->name = $request->name;
    $shop->information = $request->information;
    $shop->filename = $request->filename;
    $shop->is_selling = $request->is_selling;
    $shop->save();

    return to_route('owner.shops.index')->with([
      'message' => '更新しました',
      'status' => 'message'
    ]);
  }
}
