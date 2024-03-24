<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PrimaryCategory;
use App\Models\SecondaryCategory;
use Inertia\Inertia;
use Illuminate\Validation\Rule;
use Throwable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class CategoriesController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth:admin');
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $categories = PrimaryCategory::with(['secondary' => function ($query) {
      $query->orderBy('sort_order');
    }])
      ->orderBy('sort_order')
      ->select('id', 'name', 'sort_order')
      ->get();

    return Inertia::render('Admin/Categories/Index', [
      'categories' => $categories,
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

    $request->validate([
      'name' => [
        'required',
        //where()でクエリビルダのインスタンスが作成され、クロージャで関数を行う。$queryはみんなにとってわかりやすいインスタンス名で、whereが自動でつくるインスタンスを明示している。
        //これは、primary_category_id内での重複を確認している
        Rule::unique('secondary_categories')->where(function ($query) use ($request) {
          return $query->where('primary_category_id', $request->input('primary_category_id'));
          //エラーメッセージはlang/valida...にある。
        }),
      ],
    ]);

    try {
      //closureの中で変数を使う場合、use($value)で使える。
      DB::transaction(function () use ($request) {
        SecondaryCategory::create([
          'name' => $request->name,
          'sort_order' => $request->insertSortOrder,
          'primary_category_id' => $request->primary_category_id,
        ]);
      }, 3);
    } catch (Throwable $e) {
      //エラーがでたら例外処理
      Log::error($e);
      throw $e;
    }
    return to_route('admin.categories.index')->with([
      'message' => '登録しました',
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
    $primaryCategoryName = PrimaryCategory::findOrFail($id)->name;
    $categories = SecondaryCategory::with('primary')->orderBy('sort_order')->where('primary_category_id', $id)->select('id', 'name', 'primary_category_id')->get();
    return Inertia::render('Admin/Categories/Edit', [
      'primaryCategoryName' => $primaryCategoryName,
      'categories' => $categories,
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
    $primary_category_id = $request->categories[0]['primary_category_id'];

    try {
      //closureの中で変数を使う場合、use($value)で使える。
      DB::transaction(function () use ($request) {
        $categories = $request->categories;
        $count = count($categories);
        for ($i = 0; $i < $count; $i++) {
          $targetRecord = SecondaryCategory::findOrFail($categories[$i]['id']);
          $targetRecord->sort_order = $i + 1;
          $targetRecord->save();
        }
      }, 3);
    } catch (Throwable $e) {
      //エラーがでたら例外処理
      Log::error($e);
      throw $e;
    }
    return redirect()->route('admin.categories.edit', ['category' => $primary_category_id])->with([
      'message' => '保存しました',
      'status' => 'message',
    ]);
    // return to_route('admin.categories.index')->with([
    //   'message' => '登録しました',
    //   'status' => 'message'
    // ]);


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
    $category = SecondaryCategory::findOrFail($id);

    $primary_category_id = $category->primary_category_id;

    // 削除されるレコードのsort_orderを取得
    $deletedSortOrder = $category->sort_order;
    $category->delete();
    // 削除されるレコードの後のレコードのsort_orderを更新
    SecondaryCategory::where('primary_category_id', $primary_category_id)->where('sort_order', '>', $deletedSortOrder)->decrement('sort_order');

    return redirect()->route('admin.categories.edit', ['category' => $primary_category_id])->with([
      'message' => '削除しました',
      'status' => 'alert',
    ]);

    // return to_route('admin.categories.index')->with([
    //   'message' => 'カテゴリーを削除しました',
    //   'status' => 'alert'
    // ]);
  }
}
