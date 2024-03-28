<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Shop;
use App\Models\Image;
use App\Models\Stock;
use App\Models\User;
use App\Models\SecondaryCategory;
use Illuminate\Support\Facades\DB;
use App\Constants\Common;


class Product extends Model
{
  use HasFactory;
  protected $fillable = [
    'shop_id',
    'name',
    'information',
    'price',
    'is_selling',
    'sort_order',
    'secondary_category_id',
    'image1',
    'image2',
    'image3',
    'image4',
  ];


  public function shop()
  {
    return $this->belongsTo(Shop::class);
  }

  public function category()
  {
    //メソッド名をカラム名と異なるものにしたときは第に引数で指定する

    return $this->belongsTo(SecondaryCategory::class, 'secondary_category_id');
  }
  public function imageFirst()
  {
    //そもそもカラム名に_idとないもの、idが違うものは、第三引数で、何を指定するか書く。Imageモデルのidが外部キー
    return $this->belongsTo(Image::class, 'image1', 'id');
  }
  public function imageSecond()
  {
    return $this->belongsTo(Image::class, 'image2', 'id');
  }
  public function imageThird()
  {
    return $this->belongsTo(Image::class, 'image3', 'id');
  }
  public function imageFourth()
  {
    return $this->belongsTo(Image::class, 'image4', 'id');
  }
  public function stock()
  {
    return $this->hasMany(Stock::class);
  }

  public function users()
  {
    return $this->belongsToMany(User::class, 'carts')->withPivot('id', 'quantity');
  }

  //以下はローカルスコープ。リレーションが複雑でわかりにくいので、controllerの方はEloquantで処理している。クエリビルダで処理したい場合はこちらに書いておくのでローカルスコープを使う。
  public function scopeAvailableItems($query)
  {
    $stocks = DB::table('t_stocks')
      ->select(
        'product_id',
        DB::raw('sum(quantity) as quantity')
      )
      ->groupBy('product_id')
      ->having('quantity', '>', 1);

    //joinは('自分の好きな名前', 'table.外部キー' '==' '外部キー(上でつけた名前).リレーしているコラム')で取得できる
    //外部キーのテーブルはその前に取得しておいて、そのときには自分の好きな名前.r
    return $query
      ->joinSub($stocks, 'stock', function ($join) {
        $join->on('products.id', '=', 'stock.product_id');
      })
      ->join('shops', 'products.shop_id', '=', 'shops.id')
      ->join('secondary_categories', 'products.secondary_category_id', '=', 'secondary_categories.id')
      ->join('primary_categories', 'secondary_categories.primary_category_id', '=', 'primary_categories.id')
      ->join('images as image1', 'products.image1', '=', 'image1.id')
      ->where('shops.is_selling', true)
      ->where('products.is_selling', true)
      ->select(
        'products.id as id',
        'products.name as name',
        'products.price',
        'products.sort_order as sort_order',
        'products.information',
        'secondary_categories.name as category',
        'primary_categories.name as primaryname',
        'image1.filename as filename'
      );
  }

  //ソートオーダーのスコープ
  public function scopeSortOrder($query, $sortOrder)
  {

    if ($sortOrder === null || $sortOrder === Common::SORT_ORDER['recommend']) {
      return $query->orderBy('sort_order', 'asc');
    }
    if ($sortOrder === Common::SORT_ORDER['higherPrice']) {
      return $query->orderBy('price', 'desc');
    }
    if ($sortOrder === Common::SORT_ORDER['lowerPrice']) {
      return $query->orderBy('price', 'asc');
    }
    if ($sortOrder === Common::SORT_ORDER['later']) {
      return $query->orderBy('products.created_at', 'desc');
    }
    if ($sortOrder === Common::SORT_ORDER['older']) {
      return $query->orderBy('products.created_at', 'asc');
    }
  }

  public function scopeSelectCategory($query, $categoryId)
  {
    if ($categoryId !== '0') {
      return $query->where('secondary_category_id', $categoryId);
    } else {
      return;
    }
  }

  public function scopeSearchKeyword($query, $keyword)
  {
    if ($keyword !== '') {
      $spaceConvert = mb_convert_kana($keyword);
      $keywords = preg_split('/[\s]+/', $spaceConvert, -1, PREG_SPLIT_NO_EMPTY);
      foreach ($keywords as $word) {
        $query->where('products.name', 'like', '%' . $word . '%');
      }
      //or検索はorwhereにする
      // foreach ($keywords as $word) {
      //   $query->orWhere('products.name', 'like', '%' . $word . '%');
      // }

      return $query;
    } else {
      return;
    }
  }
}
