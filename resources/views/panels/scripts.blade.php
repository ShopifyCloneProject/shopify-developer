{{-- Vendor Scripts --}}
<script src="{{ asset(mix('vendors/js/vendors.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/ui/prism.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/extensions/sweetalert2.all.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/extensions/polyfill.min.js')) }}"></script>
@yield('vendor-script')
{{-- Theme Scripts --}}
<script src="{{ asset(mix('js/core/app-menu.js')) }}"></script>
<script src="{{ asset(mix('js/core/app.js')) }}"></script>
@if($configData['blankPage'] === false)
<script src="{{ asset(mix('js/scripts/customizer.js')) }}"></script>
@endif
<script src="/js/lang.js"></script>
{{-- page script --}}
<script src="{{ asset(mix('js/scripts/extensions/ext-component-sweet-alerts.js')) }}"></script>
<script type="text/javascript">
const APP_URL = '{{ Config::get('app.url') }}';
const API_URL = '{{ Config::get('CALL_APP_URL') }}';
const FRONT_API_URL = '{{ Config::get('CALL_FRONT_APP_URL') }}';
const CLIENT_ID = '{{ Config::get('client_id') }}';
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
</script>
<script src="{{ asset(mix('adminassets/js/common/common.min.js')) }}"></script>
@yield('page-script')
{{-- page script --}}
<script type="text/javascript">
$(document).ready(function(){
    $("#main-menu-navigation .nav-item").each(function(item){ 
        let menuClass = $(this).data('menu');
        let menuClassLength = $("."+menuClass+" ul .first_sub_menu").length;
        if(menuClassLength > 0)
        {
                $("."+menuClass+" .badge").html(menuClassLength);
        }
    });
});


</script>
