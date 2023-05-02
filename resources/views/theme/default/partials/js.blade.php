<script src="/theme/default/js/vendor-special/lazysizes.min.js"></script>
<script src="/theme/default/js/vendor-special/ls.bgset.min.js"></script>
<script src="/theme/default/js/vendor-special/ls.aspectratio.min.js"></script>
<script src="/theme/default/js/vendor-special/jquery.ez-plus.js"></script>
<script src="/theme/default/js/vendor-special/instafeed.min.js"></script>
<script src="/theme/default/js/vendor/vendor.min.js"></script>
<script src="/theme/default/js/vendor/bootstrap.bundle.min.js"></script>
<script src="/js/frontlang.js"></script>
<script src="{{ asset(mix('adminassets/js/common/common.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/ui/blockUI.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/extensions/sweetalert2.all.min.js')) }}"></script>
@yield('page-script')
<script src="/theme/default/js/jquery.zoom.js"></script>
<script type="text/javascript">
	window.THEME = {};
	$.ajaxSetup({
	    headers: {
	        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	    }
	});
</script>
<script src="{{ asset(mix('js/app.js')) }}"></script>
<script src="/theme/default/js/app-html.js"></script> 