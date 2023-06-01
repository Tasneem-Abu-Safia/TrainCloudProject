<!DOCTYPE html>
<html lang="en">
<head>
    <base href="../../../">
    <meta charset="utf-8"/>
    <title>
        Login Page | Train</title>
    <meta name="description" content="Login page example"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <link rel="canonical" href="https://keenthemes.com/metronic"/>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700"/>
    <link href="{{asset('admin/assets/css/pages/login/login-2.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('admin/assets/plugins/global/plugins.bundle.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('admin/assets/plugins/custom/prismjs/prismjs.bundle.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('admin/assets/css/style.bundle.css')}}" rel="stylesheet" type="text/css"/>
    <link rel="shortcut icon" href="{{asset('admin/assets/media/logos/LOGO4.png')}}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>
<body id="kt_body"
      class="header-fixed header-mobile-fixed subheader-enabled subheader-fixed aside-enabled aside-fixed aside-minimize-hoverable page-loading">
<div class="d-flex flex-column flex-root">
    <div class="login login-2 login-signin-on d-flex flex-column flex-lg-row flex-column-fluid bg-white" id="kt_login">
        <!--begin::Aside-->
        <div class="login-aside order-2 order-lg-1 d-flex flex-row-auto position-relative overflow-hidden">
            <!--begin: Aside Container-->
            <div class="d-flex flex-column-fluid flex-column justify-content-between py-9 px-7 py-lg-13 px-lg-35">
                <div class="d-flex flex-column-fluid flex-column flex-center">
                    <!--begin::Signin-->
                    <div class="login-form login-signin py-11">
                        @if(session()->has('error'))
                            <div class="alert alert-danger" id="msg">
                                {{ session()->get('error') }}
                            </div>
                        @endif
                        @if(session()->has('success'))
                            <div class="alert alert-success" id="msg">
                                {{ session()->get('success') }}
                            </div>
                        @endif
                        <form method="POST" action="{{ route('LoginPost') }}" class="form">
                        @csrf
                        <!--begin::Title-->
                            <div class="text-center pb-8">
                                <h2 class="font-weight-bolder text-dark font-size-h2 font-size-h1-lg">Sign In</h2>
                                <span class="text-muted font-weight-bold font-size-h4">Or
                                <a href="{{ route('register') }}" class="text-primary font-weight-bolder"
                                   id="create-account-link">Create An Account</a></span>
                            </div>
                            <!--end::Title-->
                            <!--begin::Form group-->
                            <div class="form-group">
                                <label for="userName" class="font-size-h6 font-weight-bolder text-dark">User
                                    Name</label>
                                <input
                                    class="form-control {{ $errors->has('userName') ? 'is-invalid' : '' }}
                                        form-control-solid h-auto py-7 px-6 rounded-lg"
                                    type="text"
                                    name="userName" value="{{ old('userName') }}" required autocomplete="userName"
                                    autofocus/>
                                @if($errors->has('userName'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('userName') }}
                                    </div>
                                @endif
                            </div>
                            <!--end::Form group-->
                            <!--begin::Form group-->
                            <div class="form-group">
                                <div class="d-flex justify-content-between mt-n5">
                                    <label class="font-size-h6 font-weight-bolder text-dark pt-5">Password</label>
                                    <a href="javascript:;" id="forgotPassword"
                                       class="text-primary font-size-h6 font-weight-bolder text-hover-primary pt-5">Forgot
                                        Password ?</a>
                                </div>
                                <input
                                    class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }} form-control-solid h-auto py-7 px-6 rounded-lg"
                                    type="password" name="password" required autocomplete="current-password"/>
                                @if($errors->has('password'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('password') }}
                                    </div>
                                @endif
                            </div>
                            <!--end::Form group-->
                            <!--begin::Action-->
                            <div class="text-center pt-2">
                                <button type="submit" id="kt_login_signin_submit"
                                        class="btn btn-dark font-weight-bolder font-size-h6 px-8 py-4 my-3">Sign In
                                </button>
                            </div>
                            <!--end::Action-->
                        </form>


                    </div>

                </div>
            </div>
            <!--end: Aside Container-->
        </div>
        <!--begin::Aside-->
        <!--begin::Content-->
        <div class="content order-1 order-lg-2 d-flex flex-column w-100 pb-0" style="background-color: #B1DCED;">
            <!--begin::Title-->
            <div
                class="d-flex flex-column justify-content-center text-center  pt-md-5 pt-sm-5 px-lg-0 pt-5 px-7">
                <h3 class="display4 font-weight-bolder my-7 text-dark" style="color: #986923;">Training Management
                    Application</h3>
            </div>
            <!--end::Title-->
            <!--begin::Image-->
            <div class="content-img d-flex flex-row-fluid bgi-no-repeat bgi-position-y-center bgi-position-x-center"
                 style="background-image: url({{asset('admin/assets/media/logos/LOGO4.png')}});
                     margin-top: -180px">
            </div>
            <!--end::Content-->
        </div>
        <!--end::Login-->
    </div>
</div>
<div class="modal" id="forgotPasswordModal" tabindex="-1" role="dialog" aria-labelledby="forgotPasswordModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="forgotPasswordModalLabel">Forgot Password</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('forgotPassword') }}">
                    @csrf
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Send Password Reset Email</button>
                    <button type="button" class="btn btn-secondary closeForgot">Close</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $("#msg").show().delay(3000).fadeOut();

    document.addEventListener("DOMContentLoaded", function () {
        const forgotPasswordLink = document.getElementById("forgotPassword");
        const forgotPasswordModal = document.getElementById("forgotPasswordModal");

        forgotPasswordLink.addEventListener("click", function () {
            if (typeof bootstrap !== "undefined") {
                // Use Bootstrap's modal function if available
                new bootstrap.Modal(forgotPasswordModal).show();
            } else {
                // Fallback to a simple show/hide mechanism
                forgotPasswordModal.style.display = "block";
            }
        });

        // Close the modal when the close button is clicked
        const closeModalButton = forgotPasswordModal.querySelector(".closeForgot");
        closeModalButton.addEventListener("click", function () {
            if (typeof bootstrap !== "undefined") {
                // Use Bootstrap's modal function if available
                new bootstrap.Modal(forgotPasswordModal).hide();
            } else {
                // Fallback to a simple show/hide mechanism
                forgotPasswordModal.style.display = "none";
            }
        });
    });
</script>

</body>
</html>
