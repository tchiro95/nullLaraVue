<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;


class ImageService
{
  public static function upload($imageFile, $folderName)
  {
    if (is_array($imageFile)) {
      $file = $imageFile['image'];
    } else {
      $file = $imageFile;
    }
    // 以下interventionimageのマニュアルの通り
    // create image manager with desired driver
    $manager = new ImageManager(new Driver());
    // read image from file system
    $image = $manager->read($file);
    $image->resize(1920, 1080);
    //このあとエンコードする。
    // encode as the originally read image format
    $encoded = $image->encode(); // Intervention\Image\EncodedImage
    //encodeすることで、文字列になる。アップするのに文字列じゃないとだめ。

    //被らないファイル名を作る
    $fileName = uniqid(rand() . '_');
    $extension = $file->extension();
    $fileNameToStore = $fileName . '.' . $extension;

    //追加　ファイルを更新するなら昔のファイルは消したい。

    //画像はputになるが、ファイル名を指定しないといけない。上はそのためにファイル名を作ってる
    Storage::put('public/' . $folderName . $fileNameToStore, $encoded);

    return $fileNameToStore;
  }

  public static function delete($folderName, $oldfilename)
  {
    if (Storage::exists('public/' . $folderName . $oldfilename)) {
      Storage::delete('public/' . $folderName . $oldfilename);
    }
  }
}
