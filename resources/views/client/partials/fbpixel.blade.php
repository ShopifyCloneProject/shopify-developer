<!-- Facebook Pixel Code -->
@php
  $currentRoute =   Route::currentRouteName();
@endphp
<script>
  !function(f,b,e,v,n,t,s)
  {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
  n.callMethod.apply(n,arguments):n.queue.push(arguments)};
  if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
  n.queue=[];t=b.createElement(e);t.async=!0;
  t.src=v;s=b.getElementsByTagName(e)[0];
  s.parentNode.insertBefore(t,s)}(window, document,'script',
  'https://connect.facebook.net/en_US/fbevents.js');
</script>
@if(isset($pixeldata) && $pixeldata->IsNotEmpty())
  @foreach($pixeldata as $key => $objPixel)
    @if($currentRoute == 'client.product-detail')
    @php
      $productData = $data['productdetail'];
      $productTitle = $productData['title'];
      $productCategory = $productData['collections'][0]['title'];
      $productId = $productData['id'];
      $productPrice = $productData['original_price'];
    @endphp
    <script>
          var fbpixel = '{{ $objPixel['pixel'] }}';
         fbq('init', fbpixel);
          fbq('track', 'ViewContent', {
            content_name: '{{ $productTitle }}',
            content_category: '{{ $productCategory }}',
            content_ids: ['{{ $productId }}'],
            content_type: 'product',
            value: '{{ $productPrice }}',
            currency: 'INR'
           });
        </script>
    @endif
    @if(in_array($currentRoute, ['client.home','client.product-detail']))
        <script>
          var fbpixel = '{{ $objPixel['pixel'] }}';
          fbq('init', fbpixel);
          fbq('track', 'PageView');
        </script>
    @endif
   @if(in_array($currentRoute, ['thankyou']))
    @php
      $carttotal = $data['order']->total;
      $orderData = $data['orderData'];
    @endphp
        <script>
          var fbpixel = '{{ $objPixel['pixel'] }}';
          var orderData = @json($orderData);
          fbq('init', fbpixel);
          fbq('track', 'Purchase', {
            contents: orderData,
            content_type: 'product',
            value: '{{ $carttotal }}',
            currency: 'INR'
           });
        </script>
    @endif  
  @endforeach
  @endif

<!-- End Facebook Pixel Code -->