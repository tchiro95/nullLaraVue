<p class="mb-4">{{ $product['ownerName'] }} 様</p>

<p class="mb-4">下記の注文がありました。</p>

商品内容
<ul class="mb-4">
  <li>商品名: {{ $product['name'] }}</li>
  <li>商品金額: {{ number_format($product['price'])}}円</li>
  <li>商品数: {{ $product['quantity'] }}</li>
  <li>合計金額: {{ number_format($product['price'] * $product['quantity']) }}円</li>
</ul>

購入者情報
<ul>
  <li>購入者：{{$user->name}}</li>
  <li>email：{{$user->email}}</li>
</ul>
