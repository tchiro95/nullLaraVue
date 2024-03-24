<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Shop;
use App\Models\Image;
use App\Models\Stock;
use App\Models\User;
use App\Models\SecondaryCategory;


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
}
