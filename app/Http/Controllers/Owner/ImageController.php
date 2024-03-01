<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Image;
use App\Models\Owner;
use App\Models\Shop;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

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
          return view(404);
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
    $images = Image::where('owner_id', Auth::id())->orderby('upload_at', 'disc')->pagenate(20);

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
