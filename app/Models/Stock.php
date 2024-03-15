<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
  use HasFactory;
  protected $fillable = [
    'product_id',
    'quantity',
    'type',
  ];



  //tableをmodel名と変える場合は、このようにする。t_はtransactionのt_で、アクセスが激しい場合は参照・閲覧用とは分ける
  protected $table = 't_stocks';
}
