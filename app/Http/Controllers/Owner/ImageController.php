<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Image;
use App\Models\Product;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\UploadMultipulImageRequest;
use App\Services\ImageService;



class ImageController extends Controller
{
  public function __construct()
  {
    //middlewareはレンダリング前に機能するメソッド
    $this->middleware('auth:owners');

    $this->middleware(function ($request, $next) {
      //route()->parameter()で取れるurlのidとauth::idでとれるログインIDを比べる。
      //routeのidは文字列、authは数字なのでフォーマットする
      $id = $request->route()->parameter('image');
      if (!is_null($id)) {
        $imageOwnerID = Image::findOrFail($id)->owner->id;
        $imageID = (int)$imageOwnerID;
        $ownerID = Auth::id();
        if ($imageID !== $ownerID) {

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
    $images = Image::where('owner_id', Auth::id())->orderby('updated_at', 'desc')->paginate(20);

    return Inertia::render('Owner/Images/Index', [
      'images' => $images,
    ]);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    return Inertia::render('Owner/Images/Create');
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(UploadMultipulImageRequest $request)
  {
    $folderName = 'products/';
    $imageFiles = $request->file('image');
    if (!is_null($imageFiles)) {
      foreach ($imageFiles as $imageFile) {
        $fileNameToStore = ImageService::upload($imageFile, $folderName);
        Image::create([
          "owner_id" => Auth::id(),
          "filename" => $fileNameToStore,
        ]);
      }
    }
    return to_route('owner.images.index')->with([
      'message' => '画像を保存しました',
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
    $image = Image::findOrFail($id);
    return Inertia::render('Owner/Images/Edit', [
      'image' => $image,
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
    // imageのvalidationは上のカスタムリクエストで行う。
    $request->validate([
      'title' => 'string|max:30',
    ]);

    $image = Image::findOrFail($id);
    $image->title = $request->title;
    $image->save();

    return to_route('owner.images.index')->with([
      'message' => '画像情報を更新しました',
      'status' => 'message'
    ]);
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
    $image = Image::findOrFail($id);

    $imageInProducts = Product::where('image1', $image->id)
      ->orWhere('image2', $image->id)
      ->orWhere('image3', $image->id)
      ->orWhere('image4', $image->id)
      ->get();
    if ($imageInProducts) {
      // eachでforeachみたいなことができる。
      //　useはクロージャの前で宣言された変数の値をクロージャの中で保持するために使う。$imageがんこうされても、削除されても関数の中では保存され繰り返し使える。
      $imageInProducts->each(function ($product) use ($image) {
        if ($product->image1 === $image->id) {
          $product->image1 = null;
          $product->save();
        }
        if ($product->image2 === $image->id) {
          $product->image2 = null;
          $product->save();
        }
        if ($product->image3 === $image->id) {
          $product->image3 = null;
          $product->save();
        }
        if ($product->image4 === $image->id) {
          $product->image4 = null;
          $product->save();
        }
      });
    }

    $filename = $image->filename;
    $folderName = 'products/';



    $image->delete();

    // まずは旧ファイルを削除
    ImageService::delete($folderName, $filename);

    return to_route('owner.images.index')->with([
      'message' => '画像を削除しました',
      'status' => 'alert'
    ]);
  }
}
