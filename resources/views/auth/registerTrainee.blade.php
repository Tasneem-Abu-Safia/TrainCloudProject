<!DOCTYPE html>
<html lang="en">
<!--begin::Head-->
<head>
    <meta charset="utf-8"/>
    <title>Register | Trainee</title>
    <meta name="description" content="Login page example"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <link rel="canonical" href="https://keenthemes.com/metronic"/>

    <!--begin::Fonts-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700"/>
    <!--end::Fonts-->


    <!--begin::Page Custom Styles(used by this page)-->
    <link href="{{asset('admin/assets/css/pages/login/login-1.css')}}?v=7.2.9" rel="stylesheet" type="text/css"/>
    <!--end::Page Custom Styles-->

    <!--begin::Global Theme Styles(used by all pages)-->
    <link href="{{asset('admin/assets/plugins/global/plugins.bundle.css')}}?v=7.2.9" rel="stylesheet" type="text/css"/>
    <link href="{{asset('admin/assets/plugins/custom/prismjs/prismjs.bundle.css')}}?v=7.2.9" rel="stylesheet"
          type="text/css"/>
    <link href="{{asset('admin/assets/css/style.bundle.css')}}?v=7.2.9" rel="stylesheet" type="text/css"/>
    <!--end::Global Theme Styles-->

    <!--begin::Layout Themes(used by all pages)-->

    <link href="{{asset('admin/assets/css/themes/layout/header/base/light.css')}}?v=7.2.9" rel="stylesheet"
          type="text/css"/>
    <link href="{{asset('admin/assets/css/themes/layout/header/menu/light.css')}}?v=7.2.9" rel="stylesheet"
          type="text/css"/>
    <link href="{{asset('admin/assets/css/themes/layout/brand/dark.css')}}?v=7.2.9" rel="stylesheet" type="text/css"/>
    <link href="{{asset('admin/assets/css/themes/layout/aside/dark.css')}}?v=7.2.9" rel="stylesheet" type="text/css"/>
    <!--end::Layout Themes-->

    <link rel="shortcut icon" href="{{asset('admin/assets/media/logos/favicon.ico')}}"/>

    <!-- Hotjar Tracking Code for keenthemes.com -->
</head>
<!--end::Head-->

<!--begin::Body-->
<body class="header-fixed header-mobile-fixed subheader-enabled subheader-fixed aside-enabled aside-fixed aside-minimize-hoverable page-loading">
<!--begin::Main-->
<div class="d-flex flex-column flex-root">
    <!--begin::Login-->
    <div class="login login-1 login-signin-on d-flex flex-column flex-lg-row flex-column-fluid bg-white" id="kt_login">
        <!--begin::Content-->
        <div style="background-color: #B1DCED;"
            class="login-content flex-row-fluid d-flex flex-column justify-content-center position-relative overflow-hidden p-7 mx-auto">
            <!--begin::Content body-->
            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                <div class="card card-custom d-flex flex-column-fluid flex-center">
                    <div class="card-header">
                        <h3 class="card-title" style="color: #74cbe8;">
                            Sign Up
                        </h3>
                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a href="{{ route('registerTrainee') }}" class="nav-link active">As Trainee</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('registerAdvisor') }}" class="nav-link">As Advisor</a>
                            </li>
                        </ul>
                    </div>
                    <!--begin::Form-->
                    <form method="POST" action="{{ route('postTrainee') }}" class="form">
                        @csrf
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-2 col-form-label">Text</label>
                                <div class="col-10">
                                    <input class="form-control" type="text" value="Artisanal kale"
                                           id="example-text-input"/>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="example-search-input" class="col-2 col-form-label">Search</label>
                                <div class="col-10">
                                    <input class="form-control" type="search" value="How do I shoot web"
                                           id="example-search-input"/>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="example-email-input" class="col-2 col-form-label">Email</label>
                                <div class="col-10">
                                    <input class="form-control" type="email" value="bootstrap@example.com"
                                           id="example-email-input"/>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="example-url-input" class="col-2 col-form-label">URL</label>
                                <div class="col-10">
                                    <input class="form-control" type="url" value="https://getbootstrap.com"
                                           id="example-url-input"/>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="example-tel-input" class="col-2 col-form-label">Telephone</label>
                                <div class="col-10">
                                    <input class="form-control" type="tel" value="1-(555)-555-5555"
                                           id="example-tel-input"/>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="example-password-input" class="col-2 col-form-label">Password</label>
                                <div class="col-10">
                                    <input class="form-control" type="password" value="hunter2"
                                           id="example-password-input"/>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="example-number-input" class="col-2 col-form-label">Number</label>
                                <div class="col-10">
                                    <input class="form-control" type="number" value="42" id="example-number-input"/>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="example-date-input" class="col-2 col-form-label">Date</label>
                                <div class="col-10">
                                    <input class="form-control" type="date" value="2011-08-19" id="example-date-input"/>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="example-time-input" class="col-2 col-form-label">Time</label>
                                <div class="col-10">
                                    <input class="form-control" type="time" value="13:45:00" id="example-time-input"/>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-2">
                                </div>
                                <div class="col-10">
                                    <button type="submit" style="background-color: #5da7c4;"
                                            class="btn mr-2 font-weight-bolder px-8 py-4 my-3 text-white">Sign Up
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!--end::Content-->
    </div>
    <!--end::Login-->
</div>
<!--end::Main-->
</body>
<!--end::Body-->
</html>
