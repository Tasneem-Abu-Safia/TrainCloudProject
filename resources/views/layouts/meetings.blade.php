@extends('Dashboard.master')

@section('title')
    Meetings
@endsection

@section('subTitle')
    Meetings
@endsection

@section('Page-title')
    Meetings
@endsection

@section('js')
    <script type="text/javascript">
        $("#msg").show().delay(3000).fadeOut();
        $(function () {
            let modalDelete = $('#deleteModal');
            var table = $('.meetings_datatable');
            table.DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('meetings.index') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', width: '5%'},
                    {data: 'trainee', name: 'trainee', width: '20%'},
                    {data: 'advisor', name: 'advisor', width: '20%'},
                    {data: 'datetime', name: 'datetime', width: '15%'},
                    {data: 'status', name: 'status', width: '10%'},
                    {data: 'details', name: 'details', width: '30%'},
                    {data: 'action', name: 'action', orderable: false, searchable: false, width: '10%'},
                ],
                responsive: true,
            });

            table.on('click', '.mainDelete', function (e) {
                e.preventDefault();
                var id = $(this).data('id');
                modalDelete.modal('show');
                modalDelete.find('#deleteForm').attr('action', function () {
                    var URL = "{{ route('meetings.destroy', 'x') }}";
                    return URL.replace('x', id);
                });
            });

            $('#deleteModal #cancelModal').on('click', function (e) {
                modalDelete.modal('hide');
            });

            // Create Meeting Modal
            $('.addMeeting').click(function () {
                // Reset the form fields
                $('#createMeetingForm')[0].reset();
                $('#advisor_id').empty();

                // Fetch the advisors for the trainee
                $.ajax({
                    url: "{{ route('trainee.advisors') }}",
                    type: 'GET',
                    success: function (response) {
                        $.each(response, function (key, advisor) {
                            $('#advisor_id').append('<option value="' + advisor.id + '">' + advisor.user.name + '</option>');
                        });
                    },
                    error: function (xhr, status, error) {
                        // Handle error response
                        console.log(xhr.responseText);
                    }
                });
                $('#createMeetingModal').modal('show');
            });
            $('.hideModal').click(function () {
                $('#createMeetingModal').modal('hide');
            });
            $('.hideModal1').click(function () {
                $('#emailModal').modal('hide');
            });
            $('.hideModal2').click(function () {
                $('#statusModal').modal('hide');
            });

            table.on('click', '.sendEmail', function (e) {
                $('#emailModal').modal('show');
                var meetingId = $(this).data('id');
                $('#emailForm').submit(function (e) {
                    e.preventDefault();
                    // Retrieve updated field name from the input
                    var content = $('#emailContent').val();
                    // Make an AJAX request to update the field
                    $.ajax({
                        url: "/meetings/send-email",
                        type: "POST",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "_method": "POST",
                            "meeting_id": meetingId,
                            "email_content": content,
                        },
                        success: function (response) {
                            console.log(response)
                            $('#emailModal').modal('hide');
                            $('.meetings_datatable').DataTable().ajax.reload();
                        },
                        error: function (xhr, status, error) {
                            // Handle error response, such as displaying an error message
                        }
                    });
                });
            });


            table.on('click', '.updateStatus', function (e) {
                $('#statusModal').modal('show');
                var meetingId = $(this).data('id');
                $('#statusForm').submit(function (e) {
                    e.preventDefault();
                    // Retrieve updated field name from the input
                    var status = $('#statusSelect').val();
                    // Make an AJAX request to update the field
                    $.ajax({
                        url: "/meetings/updateStatus",
                        type: "POST",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "_method": "POST",
                            "meetingId": meetingId,
                            "status": status,
                        },
                        success: function (response) {
                            console.log(response)
                            $('#statusModal').modal('hide');
                            $('.meetings_datatable').DataTable().ajax.reload();
                        },
                        error: function (xhr, status, error) {
                            // Handle error response, such as displaying an error message
                        }
                    });
                });
            });

            $('#createMeetingForm').submit(function (event) {
                event.preventDefault();

                // Get the form values
                var advisorId = $('#advisor_id').val();
                var date = $('#date').val();
                var time = $('#time').val();
                var details = $('#details').val();

                // Create the meeting
                $.ajax({
                    type: 'POST',
                    url: "{{ route('meetings.store') }}",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "advisor_id": advisorId,
                        "date": date,
                        "time": time,
                        "details": details,
                    },
                    success: function (response) {
                        // Hide the modal
                        $('#createMeetingModal').modal('hide');
                        // Reload the datatable
                        $('.meetings_datatable').DataTable().ajax.reload();
                        // Show success message
                        $('#msg').text('Meeting created successfully.').fadeIn();
                        location.reload();
                    },
                    error: function (xhr, status, error) {
                        // Handle error response
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
                @if(session()->has('msg'))
                    <div class="alert alert-success" id="msg">
                        {{ session()->get('msg') }}
                    </div>
                @endif
                <div class="card card-custom">
                    <div class="card-header flex-wrap py-5">
                        <div class="card-title">
                            <h3 class="card-label">Meetings List</h3>
                        </div>
                        @if(\Illuminate\Support\Facades\Auth::user()->guard == 'trainee')
                            <div class="card-toolbar">
                                <div class="d-flex align-items-center position-relative my-1"
                                     data-kt-view-roles-table-toolbar="base">
                                    <button type="button"
                                            class="addMeeting btn btn-sm btn-light-primary er fs-6 px-8 py-4"
                                            data-bs-toggle="modal" data-bs-target="#createMeetingModal">
                                        <i class="la la-plus"></i> Create new Meeting
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="card-body">
                        <!--begin: Datatable-->
                        <table class="table table-separate table-head-custom table-checkable meetings_datatable"
                               id="kt_datatable">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Trainee</th>
                                <th>Advisor</th>
                                <th>Date & Time</th>
                                <th>Status</th>
                                <th>Details</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                        <!--end: Datatable-->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Meeting Modal -->
    <div class="modal fade" id="createMeetingModal" tabindex="-1" role="dialog"
         aria-labelledby="createMeetingModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createMeetingModalLabel">Create Meeting</h5>
                </div>
                <div class="modal-body">
                    <form id="createMeetingForm">
                        <div class="mb-3">
                            <label for="advisor_id" class="form-label">Select Advisor:</label>
                            <select class="form-control" id="advisor_id" name="advisor_id" required>
                                <option value="">Select Advisor</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="date" class="form-label">Date:</label>
                            <input type="date" min="{{ date('Y-m-d') }}" class="form-control" id="date" name="date"
                                   required>
                        </div>
                        <div class="mb-3">
                            <label for="time" class="form-label">Time:</label>
                            <input type="time" class="form-control" id="time" name="time" step="any" required>
                        </div>
                        <div class="mb-3">
                            <label for="advisor_id" class="form-label">Details</label>
                            <textarea class="form-control" id="details" name="details"></textarea>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">Create</button>
                            <button type="button" class="btn btn-secondary hideModal" data-bs-dismiss="modal">Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- Send Email Modal -->
    <div class="modal fade" id="emailModal" tabindex="-1" aria-labelledby="emailModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="emailModalLabel">Send Email</h5>
                </div>
                <div class="modal-body">
                    <form id="emailForm">
                        <div class="mb-3">
                            <label for="emailContent" class="form-label">Email Content:</label>
                            <textarea class="form-control" id="emailContent" name="emailContent" rows="3"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Send</button>
                        <button type="button" class="btn btn-secondary hideModal1" data-bs-dismiss="modal">Cancel
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Update Status Modal -->
    <div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="statusModalLabel">Update Status</h5>
                </div>
                <div class="modal-body">
                    <form id="statusForm">
                        <div class="mb-3">
                            <label for="statusSelect" class="form-label">Select Status:</label>
                            <select class="form-control" id="statusSelect" name="status">
                                <option value="requested">Requested</option>
                                <option value="accepted">Accepted</option>
                                <option value="completed">Completed</option>
                                <option value="declined">Declined</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                        <button type="button" class="btn btn-secondary hideModal2" data-bs-dismiss="modal">Cancel
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
