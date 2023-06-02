<!DOCTYPE html>

<html lang="en">
<!--begin::Head-->
<head>
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-QN9K6GZ09J"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-QN9K6GZ09J');
</script>
    <base href="">
    <meta charset="utf-8"/>
    <title>
        Training Management Application
    </title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    @include('Dashboard.css')
    @yield('css')
</head>
<!--end::Head-->
<!--begin::Body-->
<body id="kt_body"
      class="header-fixed header-mobile-fixed subheader-enabled subheader-fixed aside-enabled aside-fixed aside-minimize-hoverable page-loading">
<!--begin::Main-->
<div class="d-flex flex-column flex-root">
    <!--begin::Page-->
    <div class="d-flex flex-row flex-column-fluid page">
        <!--begin::Aside-->
    @include('Dashboard.sidebar')
    @include('Dashboard.headerbar')
    <!--end::Aside-->
        <!--begin::Wrapper-->
        <div class="d-flex flex-column flex-row-fluid wrapper" id="kt_wrapper">
            <!--begin::Header-->
        @include('Dashboard.navbar')
        <!--end::Header-->
            <div class="content d-flex flex-column flex-column-fluid" id="kt_content">

                <!--begin::Content-->
            @yield('content')
            <!--end::Content-->
            </div>
            <!--begin::Footer-->
        @include('Dashboard.footer')
        <!--end::Footer-->
        </div>
        <!--end::Wrapper-->
    </div>
    <!--end::Page-->
</div>
<!--end::Main-->
<!--begin::Scrolltop-->
<div id="kt_scrolltop" class="scrolltop">
			<span class="svg-icon">
				<!--begin::Svg Icon | path:assets/media/svg/icons/Navigation/Up-2.svg-->
				<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                     height="24px" viewBox="0 0 24 24" version="1.1">
					<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
						<polygon points="0 0 24 0 24 24 0 24"/>
						<rect fill="#000000" opacity="0.3" x="11" y="10" width="2" height="10" rx="1"/>
						<path
                            d="M6.70710678,12.7071068 C6.31658249,13.0976311 5.68341751,13.0976311 5.29289322,12.7071068 C4.90236893,12.3165825 4.90236893,11.6834175 5.29289322,11.2928932 L11.2928932,5.29289322 C11.6714722,4.91431428 12.2810586,4.90106866 12.6757246,5.26284586 L18.6757246,10.7628459 C19.0828436,11.1360383 19.1103465,11.7686056 18.7371541,12.1757246 C18.3639617,12.5828436 17.7313944,12.6103465 17.3242754,12.2371541 L12.0300757,7.38413782 L6.70710678,12.7071068 Z"
                            fill="#000000" fill-rule="nonzero"/>
					</g>
				</svg>
                <!--end::Svg Icon-->
			</span>
</div>
<!--end::Scrolltop-->
@include('Dashboard.js')
@yield('js')

<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">

        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Delete</h5>
                <button type="button" style="border: none" id="cancelModal">
                    <i aria-hidden="true" class="fa fa-times"></i>
                </button>
            </div>
            <form method="post" id="deleteForm">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <h5 class="text-center">Are you Sure ?</h5>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                            id="cancelModal">Cancel
                    </button>
                    <button type="submit" class="btn btn-light-danger">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="messagePusher" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="message-modal-label"></h5>
            </div>
            <div class="modal-body">
                <!-- Display the message here -->
            </div>
            <div class="modal-footer">
                <form action="" method="post" id="messagePusherForm">
                    @csrf
                    @method('PUT')
                    <button type="submit" href="" class="btn btn-secondary" id="showRegisterPusher" data-dismiss="modal">Show</button>
                    <a type="button" class="btn btn-secondary" id="closePusher">Close</a>
                </form>
            </div>
        </div>
    </div>
</div>

<audio id="notification-sound">
    <source src="{{asset('sounds/not.mp3')}}" type="audio/mpeg">
</audio>
<!--end::Page Scripts-->
</body>
<!--end::Body-->
</html>
