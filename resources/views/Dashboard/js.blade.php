<!--end::Demo Panel-->
<script>var HOST_URL = "https://preview.keenthemes.com/metronic/theme/html/tools/preview";</script>
<!--begin::Global Config(global config for global JS scripts)-->
<script>var KTAppSettings = {
        "breakpoints": {"sm": 576, "md": 768, "lg": 992, "xl": 1200, "xxl": 1400},
        "colors": {
            "theme": {
                "base": {
                    "white": "#ffffff",
                    "primary": "#3699FF",
                    "secondary": "#E5EAEE",
                    "success": "#1BC5BD",
                    "info": "#8950FC",
                    "warning": "#FFA800",
                    "danger": "#F64E60",
                    "light": "#E4E6EF",
                    "dark": "#181C32"
                },
                "light": {
                    "white": "#ffffff",
                    "primary": "#E1F0FF",
                    "secondary": "#EBEDF3",
                    "success": "#C9F7F5",
                    "info": "#EEE5FF",
                    "warning": "#FFF4DE",
                    "danger": "#FFE2E5",
                    "light": "#F3F6F9",
                    "dark": "#D6D6E0"
                },
                "inverse": {
                    "white": "#ffffff",
                    "primary": "#ffffff",
                    "secondary": "#3F4254",
                    "success": "#ffffff",
                    "info": "#ffffff",
                    "warning": "#ffffff",
                    "danger": "#ffffff",
                    "light": "#464E5F",
                    "dark": "#ffffff"
                }
            },
            "gray": {
                "gray-100": "#F3F6F9",
                "gray-200": "#EBEDF3",
                "gray-300": "#E4E6EF",
                "gray-400": "#D1D3E0",
                "gray-500": "#B5B5C3",
                "gray-600": "#7E8299",
                "gray-700": "#5E6278",
                "gray-800": "#3F4254",
                "gray-900": "#181C32"
            }
        },
        "font-family": "Poppins"
    };</script>
<script src="http://code.jquery.com/jquery-3.3.1.min.js"></script>

<!--end::Global Config-->
<!--begin::Global Theme Bundle(used by all pages)-->
<script src="{{asset('admin/assets/plugins/global/plugins.bundle.js')}}"></script>
<script src="{{asset('admin/assets/plugins/custom/prismjs/prismjs.bundle.js')}}"></script>
<script src="{{asset('admin/assets/js/scripts.bundle.js')}}"></script>
<!--end::Global Theme Bundle-->
<!--begin::Page Vendors(used by this page)-->
<script src="{{asset('admin/assets/plugins/custom/fullcalendar/fullcalendar.bundle.js')}}"></script>
<!--end::Page Vendors-->
<!--begin::Page Scripts(used by this page)-->
<script src="{{asset('admin/assets/js/pages/widgets.js')}}"></script>
<!--begin::Page Scripts(used by this page)-->
<script src="{{asset('admin/assets/js/pages/crud/forms/editors/quill.js')}}"></script>
<!--end::Page Scripts-->
<script src="{{asset('admin/assets/js/pages/crud/forms/editors/summernote.js')}}"></script>
<script src="{{asset('admin/assets/js/pages/crud/file-upload/image-input.js')}}"></script>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>

<script src="https://js.pusher.com/7.2/pusher.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>


<script>
    $(document).ready(function () {
        Pusher.logToConsole = true;
        var pusher = new Pusher('1e58abe6fe45f3bd2e73', {
            cluster: 'ap3',
            forceTLS: true
        });
        if ({{auth()->user()->guard === 'manager'}}) {
            var channel1 = pusher.subscribe('newRegister');
            channel1.bind('new-register', function (data) {
                console.log(data)
                $('#messagePusher .modal-body').html(data.body);
                $('#messagePusher .modal-title').html(data.title);
                $('#messagePusher').modal('show');
                updateUnreadCount();
                var audio = document.getElementById('notification-sound');
                audio.play();

                $('#messagePusher #messagePusherForm').attr('action', function () {
                    var actionUrl = '{{ route("notificationsRead", ":notificationId") }}';
                    actionUrl = actionUrl.replace(':notificationId', data.Notification_id);
                    return actionUrl;
                });

                $('#showRegisterPusher').on('click', function (e) {
                    e.preventDefault(); // Prevent the default form submission
                    $('#messagePusherForm').submit();
                });

                setTimeout(function () {
                    $('#messagePusher').modal('hide');
                }, 20000);

            });
        }
        var authID = {{\Illuminate\Support\Facades\Auth::id() }};
        if ({{auth()->user()->guard === 'advisor'}}) {
            var channel2 = pusher.subscribe('advisor');
            channel2.bind('notify-advisor', function (data) {
                if (authID === data.notifiable_id) {
                    console.log(data)
                    $('#messagePusher .modal-body').html(data.body);
                    $('#messagePusher .modal-title').html(data.title);
                    $('#messagePusher').modal('show');
                    updateUnreadCount();
                    var audio = document.getElementById('notification-sound');
                    audio.play();

                    $('#messagePusher #messagePusherForm').attr('action', function () {
                        var actionUrl = '{{ route("notificationsRead", ":notificationId") }}';
                        actionUrl = actionUrl.replace(':notificationId', data.Notification_id);
                        return actionUrl;
                    });

                    $('#showRegisterPusher').on('click', function (e) {
                        e.preventDefault(); // Prevent the default form submission
                        $('#messagePusherForm').submit();
                    });

                    setTimeout(function () {
                        $('#messagePusher').modal('hide');
                    }, 20000);
                }
            });
        }
        $('#messagePusher #closePusher').on('click', function (e) {
            $('#messagePusher').modal('hide');
        });

    });

</script>
