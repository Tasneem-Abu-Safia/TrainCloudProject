@extends('Dashboard.master')
@section('title')
    My Courses
@endsection
@section('subTitle')
    My Courses
@endsection

@section('Page-title')
    My Courses
@endsection

@section('js')
    <script type="text/javascript">
        $(function () {
            $("#alert").fadeIn(500, function () {
                $(this).delay(5000).fadeOut(500);
            });
            var table = $('#course-table');
            table.DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('joined.courses') }}",
                columns: [
                    {data: 'course_num', name: 'id', width: '5%'},
                    {data: 'name', name: 'name', width: '10%'},
                    {data: 'field.name', name: 'field.name', defaultContent: '-', width: '10%'},
                    {
                        data: null,
                        render: function (data, type, row) {
                            return data.duration + ' ' + data.duration_unit;
                        },
                        name: 'duration',
                        width: '10%'
                    },
                    {data: 'start_date', name: 'start_date', width: '10%'},
                    {data: 'end_date', name: 'start_date', width: '10%'},
                    {data: 'fees', name: 'fees', width: '3%'},
                    {
                        data: 'action', name: 'action',
                        orderable: false,
                        searchable: false,
                        width: '15%'
                    },
                ],
                responsive: true, // Enable responsive behavior
            });
            $('.hideModal2').click(function () {
                $('#attendanceModal').modal('hide');
            });

            // Attendance Modal
            table.on('click', '.attendance', function () {
                var course = $(this).data('course');
                var startDate = new Date(course.start_date);
                var endDate = new Date(course.end_date);

                var modal = $('#attendanceModal');
                var daysHtml = '<div class="row">';
                var currentDate = new Date(startDate);
                var lastDate = new Date(endDate);
                var count = 0;
                var courseId = $(this).data('id');

                while (currentDate <= lastDate) {
                    var formattedDate = currentDate.toLocaleDateString('en-US', {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    });

                    var originalDate = currentDate.toISOString().split('T')[0]; // Get original date value

                    daysHtml += '<div class="col-4">' +
                        '<div class="form-check">' +
                        '<input class="form-check-input" type="checkbox" name="attendance[]" value="' + originalDate + '"' +
                        (currentDate.toISOString().split('T')[0] === new Date().toISOString().split('T')[0] ? ' checked' : '') +
                        (currentDate < new Date().setHours(0, 0, 0, 0) || currentDate > new Date().setHours(23, 59, 59, 999) ? ' disabled' : '') +
                        '>' +
                        '<label class="form-check-label">' + formattedDate + '</label>' +
                        '</div>' +
                        '</div>';

                    currentDate.setDate(currentDate.getDate() + 1);
                    count++;

                    if (count % 3 === 0) {
                        daysHtml += '</div><div class="row">';
                    }
                }

                daysHtml += '</div><br>';
                modal.find('#attendanceDays').html(daysHtml);
                modal.modal('show');

                $('#attendanceForm').submit(function (e) {
                    e.preventDefault();
                    $('#msg').removeClass('alert-success alert-danger').addClass('alert').text('').hide();
                    var attendanceData = [];
                    console.log(courseId)
                    // Retrieve the checked attendance dates
                    $('input[name="attendance[]"]:checked').each(function () {
                        attendanceData.push($(this).val());
                    });
                    console.log(attendanceData)

                    $.ajax({
                        url: "{{ route('addAttendance') }}", // Replace with the appropriate URL
                        type: "POST",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            courseId: courseId,
                            attendance: attendanceData
                        },
                        success: function (response) {
                            console.log(response)
                            $('#course-table').DataTable().ajax.reload();
                            $('#attendanceModal').modal('hide');
                            if (response.success) {
                                $('#alert').removeClass('alert-danger').addClass('alert-success').text(response.success).removeAttr('hidden');

                            } else {
                                $('#alert').removeClass('alert-success').addClass('alert-danger').text(response.error).removeAttr('hidden');

                            }
                        },
                        error: function (xhr, status, error) {
                            console.log(error)
                        }
                    });
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

                <div class="card card-custom">
                    <div class="card-header flex-wrap py-5">
                        <div class="card-title">
                            <h3 class="card-label">Courses List</h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <!--begin: Datatable-->
                        <table id="course-table" class="table">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Field</th>
                                <th>Duration</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Fees</th>
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

    <!-- Attendance Modal -->
    <div class="modal fade" id="attendanceModal" tabindex="-1" aria-labelledby="attendanceModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="attendanceModalLabel" id="modalTitle">Attendance for Course</h5>
                </div>
                <div class="modal-body">
                    <form id="attendanceForm">
                        <input type="hidden" id="courseId" name="course_id">
                        <div id="attendanceDays"></div>
                        <button type="submit" class="btn btn-primary">Save Attendance</button>
                        <button type="button" class="btn btn-secondary hideModal2" data-bs-dismiss="modal">Cancel
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
