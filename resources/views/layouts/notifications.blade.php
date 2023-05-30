@extends('Dashboard.master')
@section('title')
    Notifications
@endsection
@section('subTitle')
    Notifications
@endsection

@section('Page-title')
    Notifications
@endsection

@section('js')
    <script type="text/javascript">
        $("#msg").show().delay(3000).fadeOut();
        $(function () {
            var table = $('.notifications_datatable');
            table.DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('notifications.index') }}",
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'type', name: 'type'},
                    {
                        data: null,
                        name: 'data',
                        render: function (data) {
                            var decodedData = htmlDecode(data.data);
                            var jsonData = JSON.parse(decodedData);
                            return jsonData.body;
                        }
                    },
                    {data: 'read_at', name: 'read_at'},
                    {
                        data: 'action', name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],

            });

            function htmlDecode(input) {
                var doc = new DOMParser().parseFromString(input, "text/html");
                return doc.documentElement.textContent;
            }

            table.on('click', '.makeRead', function (e) {
                e.preventDefault();
                var id = $(this).data('id');
                var user = $(this).data('user');
                var URL = "{{ route('notificationsRead', 'x') }}";
                var new_url = URL.replace('x', id);
                $.ajax({
                    url: new_url,
                    type: "PUT",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "_method": "PUT",
                    },
                    success: function () {
                        var notificationType = $(this).data('notification-type');
                        if (notificationType === "register_Advisor") {
                            window.location.href = "{{ route('advisors.show', ':register_id') }}".replace(':register_id', user);
                        } else if (notificationType === "register_Trainee") {
                            window.location.href = "{{ route('trainees.show', ':register_id') }}".replace(':register_id', user);
                        } else if (notificationType === "assignCourse") {
                            window.location.href = "{{ route('courses.show', ':course_id') }}".replace(':course_id', user);
                        } else {
                            table.DataTable().ajax.reload();
                        }
                        updateUnreadCount();
                    }.bind(this)
                });
            });

            $('#makeAllReadButton').on('click', function () {
                $.ajax({
                    url: "{{ route('notifications.markAllRead') }}",
                    method: "PUT",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "_method": "PUT",
                    },
                    success: function (response) {
                        table.DataTable().ajax.reload();
                        updateUnreadCount();
                    },
                    error: function (xhr, status, error) {
                        console.error(error);
                    }
                });
            });

        });

        function updateUnreadCount() {
            $.ajax({
                url: "{{ route('notifications.count') }}",
                method: "GET",
                success: function (data) {
                    $('#unreadCount').text(data);
                },
                error: function (xhr, status, error) {
                    console.error(error);
                }
            });
        }
    </script>
@endsection
@section('content')

    <div class="flex-lg-row-fluid ms-lg-10">
        <!--begin::Card-->
        <div class="card card-flush mb-6 mb-xl-9">

            <div class="card-body pt-0">
                @if(session()->has('msg'))
                    <div class="alert alert-success" id="msg">
                        {{ session()->get('msg') }}
                    </div>
                @endif
                <div class="card card-custom">

                    <div class="card-header flex-wrap py-5">
                        <div class="card-title">
                            <h3 class="card-label">Notifications List</h3>
                        </div>
                        <div class="card-toolbar">
                            <div class="d-flex align-items-center position-relative my-1"
                                 data-kt-view-roles-table-toolbar="base">
                                <button type="button" id="makeAllReadButton"
                                        class="btn btn-sm btn-light-primary er fs-6 px-8 py-4">
                                    Make All Read
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!--begin: Datatable-->
                        <table class="table table-separate table-head-custom table-checkable notifications_datatable"
                               id="kt_datatable">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Type</th>
                                <th>Body</th>
                                <th>Read_at</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('js')

@endsection


