@extends('Dashboard.master')
@section('title')
    Tasks
@endsection
@section('subTitle')
    Tasks
@endsection

@section('Page-title')
    Tasks
@endsection

@section('js')
    <script type="text/javascript">
        $("#msg").show().delay(3000).fadeOut();
        $(function () {
            let modalDelete = $('#deleteModal');
            var table = $('.tasks_datatable');
            table.DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('tasks.index') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', width: '5%'},
                    {data: 'course.name', name: 'course.name', width: '15%'},
                    {data: 'title', name: 'title', width: '15%'},
                    {data: 'description', name: 'description', width: '25%'},
                    {data: 'start_date', name: 'start_date', width: '10%'},
                    {data: 'end_date', name: 'end_date', width: '10%'},
                    {data: 'mark', name: 'mark', width: '5%'},
                    {
                        data: 'file',
                        name: 'file',
                        width: '10%',
                    },
                    {data: 'action', name: 'action', orderable: false, searchable: false, width: '10%'},
                ],
                responsive: true,
            });


            table.on('click', '.mainDelete', function (e) {
                e.preventDefault();
                var id = $(this).data('id');
                modalDelete.modal('show');
                modalDelete.find('#deleteForm').attr('action', function () {
                    var URL = "{{ route('tasks.destroy', 'x') }}";
                    return URL.replace('x', id);
                });

            });

            $('#deleteModal #cancelModal').on('click', function (e) {
                modalDelete.modal('hide');
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
                            <h3 class="card-label">Tasks List </h3>
                        </div>
                        @if(\Illuminate\Support\Facades\Auth::user()->guard == 'advisor')
                            <div class="card-toolbar">
                                <div class="d-flex align-items-center position-relative my-1"
                                     data-kt-view-roles-table-toolbar="base">
                                    <a href="{{route('tasks.create')}}"
                                       class="btn btn-sm btn-light-primary er fs-6 px-8 py-4">
                                        <i class="la la-plus"></i> Create new Task
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="card-body">
                        <!--begin: Datatable-->
                        <table class="table table-separate table-head-custom table-checkable tasks_datatable"
                               id="kt_datatable">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Course</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Mark</th>
                                <th>File</th>
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


