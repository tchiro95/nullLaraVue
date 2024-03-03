<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class UploadMultipulImageRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize()
  {
    return true;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, mixed>
   */
  public function rules()
  {
    //わたってきたデータをデバックするにはlogを使う。

    // Log::debug($this->all()); // リクエストデータをログに記録する

    return [
      'files.*.image' => 'image|mimes:png,jpg,jpeg,webp',
    ];
  }

  public function message()
  {
    return [
      'image' => '指定されたファイルが画像ではありません。',
      'mimes' => '指定された拡張子（jpg,jpeg,png,webp）ではありません。'
    ];
  }
}
