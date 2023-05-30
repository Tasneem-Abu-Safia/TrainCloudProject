@extends('Dashboard.master')

@section('title')
    Tasks {{$task->id}} Submissions
@endsection

@section('subTitle')
    Tasks {{$task->id}} Submissions

@endsection

@section('Page-title')
    Tasks {{$task->id}} Submissions

@endsection

@section('js')
    <script type="text/javascript">
        $("#msg").show().delay(3000).fadeOut();

        $(function () {
            let modalDelete = $('#deleteModal');
            var table = $('#task-table');
            table.DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('tasks.submissions', $task) }}",
                columns: [
                    {data: 'id', name: 'id', width: '5%'},
                    {data: 'trainee_name', name: 'trainee_name', width: '15%'},
                    {data: 'file', name: 'file', defaultContent: '-', width: '30%'},
                    {data: 'status', name: 'status', defaultContent: '-', width: '30%'},
                    {data: 'mark', name: 'mark', defaultContent: '-', width: '10%'},
                    {
                        data: 'action', name: 'action',
                        orderable: false,
                        searchable: false,
                        width: '10%'
                    },
                ],
                responsive: true, // Enable responsive behavior

            });

            table.on('click', '.mainDelete', function (e) {
                e.preventDefault();
                var id = $(this).data('id');
                modalDelete.modal('show');
                modalDelete.find('#deleteForm').attr('action', function () {
                    var URL = "{{ route('taskSubmissions.destroy', 'x') }}";
                    return URL.replace('x', id);
                });

            });

            $('#deleteModal #cancelModal').on('click', function (e) {
                modalDelete.modal('hide');
            });

        });
        $(document).ready(function () {
            $('#markModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var submissionId = button.data('submission-id');
                var modal = $(this);
                modal.find('#markForm').attr('data-submission-id', submissionId);
            });

            $('#markForm').submit(function (event) {
                event.preventDefault();
                var submissionId = $(this).data('submission-id');
                var mark = $('#markInput').val();
                var taskId = {{ $task->id }}; // Replace with the actual task ID

                if (mark !== '' && mark <= {{ $task->mark }}) {
                    $.ajax({
                        type: 'PUT',
                        url: '/submissions/' + submissionId + '/mark',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "_method": "PUT",
                            mark: mark,
                            task_id: taskId
                        },
                        success: function (response) {
                            $('#markModal').modal('hide');
                            $('.modal-backdrop').remove();
                            $('#task-table').DataTable().ajax.reload();
                        },
                        error: function (xhr, status, error) {
                            // Handle error response
                            console.log(xhr.responseText);
                        }
                    });
                } else {
                    // Display error message for invalid mark
                    alert('Invalid mark! Please enter a value less than or equal to ' + mark);
                }
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
                            <h3 class="card-label">Task Submissions</h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <!--begin: Datatable-->
                        <table id="task-table" class="table">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Trainee</th>
                                <th>File</th>
                                <th>Status</th>
                                <th>Mark</th>
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
    <!-- Modal -->
    <div class="modal fade" id="markModal" role="dialog" aria-labelledby="markModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="markModalLabel">Mark Submission</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="markForm">
                        <div class="form-group">
                            <label for="markInput">Mark</label>
                            <input type="number" class="form-control" min="0" id="markInput" name="markInput" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
