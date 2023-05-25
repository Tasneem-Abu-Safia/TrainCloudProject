@extends('Dashboard.master')
@section('title')
    Courses
@endsection
@section('subTitle')
    Courses
@endsection

@section('Page-title')
    Courses
@endsection

@section('js')
    <script type="text/javascript">
        $("#msg").show().delay(3000).fadeOut();

        $(function () {
            let modalDelete = $('#deleteModal');
            var table = $('#course-table');
            table.DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('courses.index') }}",
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
                    {data: 'fees', name: 'fees', width: '5%'},
                    {data: 'capacity', name: 'capacity', width: '5%'},
                    {data: 'num_trainee', name: 'num_trainee', width: '5%'},
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
                    var URL = "{{ route('courses.destroy', 'x') }}";
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
                            <h3 class="card-label">Courses List </h3>
                        </div>
                        <div class="card-toolbar">
                            <div class="d-flex align-items-center position-relative my-1"
                                 data-kt-view-roles-table-toolbar="base">
                                <a href="{{route('courses.create')}}"
                                   class="btn btn-sm btn-light-primary er fs-6 px-8 py-4">
                                    <i class="la la-plus"></i> Create new Course
                                </a>
                            </div>
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
                                <th>Capacity</th>
                                <th># Registered</th>
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


