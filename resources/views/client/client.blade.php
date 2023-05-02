<html lang="en">
   @include('client/partials/head')
   <body class="has-smround-btns has-loader-bg equal-height template-product">
      @if($head_style != '')
        {!! $body_style !!}
      @endif
      <div id="app">
         @if(Session::has('message'))
             <div class="alert {{ Session::get('alert-class') }} alert-dismissible in" role="alert">
                 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                     <span aria-hidden="true">×</span>
                 </button>
                 {{ Session::get('message') }}
             </div>
         @endif
         {!! Session::forget('message') !!}

         @yield('content')
         @include('client/partials/js')
         @yield('customSection')
      </div>
   </body>
</html>