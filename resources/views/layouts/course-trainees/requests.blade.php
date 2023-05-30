@extends('Dashboard.master')
@section('title')
    Course Trainees Requests
@endsection
@section('subTitle')
    Course Trainees Requests
@endsection

@section('Page-title')
    Course Trainees Requests
@endsection

@section('js')
    <script type="text/javascript">
        $(function () {
            $("#msg").fadeIn(500, function () {
                $(this).delay(3000).fadeOut(500);
            });

            $("#alert").fadeIn(500, function () {
                $(this).delay(3000).fadeOut(500);
            });
            let modalDelete = $('#deleteModal');
            let table = $('.course_trainees');
            table.DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('course-traineesRequests') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'course.course_num', name: 'course.course_num'},
                    {data: 'course.name', name: 'course.name'},
                    {data: 'trainee.user.name', name: 'trainee.user.name'},
                    {data: 'trainee.user.email', name: 'trainee.user.email'},
                    {
                        data: 'status', name: 'status',
                        render: function (data) {
                            return data === 'active' ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>';
                        }
                    },
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ]
            });

            table.on('click', '.deleteCourseTrainee', function (e) {
                e.preventDefault();
                let courseId = $(this).data('course-id');
                let traineeId = $(this).data('trainee-id');
                modalDelete.modal('show');
                modalDelete.find('#deleteForm').attr('action', function () {
                    return '/course-trainees/destroy/' + courseId + '/' + traineeId;
                });
            });

            $('#deleteModal #cancelModal').on('click', function (e) {
                modalDelete.modal('hide');
            });
            // Activate Trainee
            table.on('click', '.btn-activate', function () {
                var courseId = $(this).data('course-id');
                var traineeId = $(this).data('trainee-id');

                $.ajax({
                    url: "/course-trainees/active/" + courseId + "/" + traineeId,
                    type: "GET",
                    success: function (response) {
                        console.log(response.message);
                        $('.course_trainees').DataTable().ajax.reload();
                        $('#alert').removeClass('alert-danger').addClass('alert-success').text(response.message).removeAttr('hidden');
                    },
                    error: function (xhr) {
                        $('#alert').removeClass('alert-success').addClass('alert-danger').text(xhr.responseText).removeAttr('hidden');
                        console.log(xhr.responseText);
                    }
                });
            });

            // Deactivate Trainee
            table.on('click', '.btn-deactivate', function () {
                var courseId = $(this).data('course-id');
                var traineeId = $(this).data('trainee-id');
                $.ajax({
                    url: "/course-trainees/inactive/" + courseId + "/" + traineeId,
                    type: "GET",
                    success: function (response) {
                        $('.course_trainees').DataTable().ajax.reload();
                        $('#alert').removeClass('alert-danger').addClass('alert-success').text(response.message).removeAttr('hidden');
                    },
                    error: function (xhr) {
                        $('#alert').removeClass('alert-success').addClass('alert-danger').text(xhr.responseText).removeAttr('hidden');
                        console.log(xhr.responseText);
                    }
                });
            });

        });
    </script>
@endsection

@section('content')
    <div class="flex-lg-row-fluid ms-lg-10">
        <!--begin::Card-->
        <div class="card card-flush mb-6 mb-xl-9">
            <div class="card-body pt-0">
                <div class="alert alert-success" hidden id="alert"></div>
                @if(session()->has('msg'))
                    <div class="alert alert-success" id="msg">
                        {{ session()->get('msg') }}
                    </div>
                @endif
                <div class="card card-custom">
                    <div class="card-header flex-wrap py-5">
                        <div class="card-title">
                            <h3 class="card-label">Course Trainees List</h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <!--begin: Datatable-->
                        <table class="table table-separate table-head-custom table-checkable course_trainees"
                               id="kt_datatable">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>CourseID</th>
                                <th>Course</th>
                                <th>Trainee Name</th>
                                <th>Trainee Email</th>
                                <th>Status</th>
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
