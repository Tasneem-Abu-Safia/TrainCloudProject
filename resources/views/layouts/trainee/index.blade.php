@extends('Dashboard.master')
@section('title')
    Trainees
@endsection
@section('subTitle')
    Trainees
@endsection

@section('Page-title')
    Trainees
@endsection

@section('js')
    <script type="text/javascript">
        $("#msg").show().delay(3000).fadeOut();

        $(function () {
            let modalDelete = $('#deleteModal');
            var table = $('.trainees_datatable');
            table.DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('trainees.index') }}",
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'user.name', name: 'user.name'},
                    {data: 'user.email', name: 'user.email'},
                    {data: 'phone', name: 'phone'},
                    {data: 'degree', name: 'degree'},
                    {
                        data: 'action', name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],

            });

            table.on('click', '.mainDelete', function (e) {
                e.preventDefault();
                var id = $(this).data('id');
                modalDelete.modal('show');
                modalDelete.find('#deleteForm').attr('action', function () {
                    var URL = "{{ route('trainees.destroy', 'x') }}";
                    return URL.replace('x', id);
                });

            });

            $('#deleteModal #cancelModal').on('click', function (e) {
                modalDelete.modal('hide');
            });


            table.on('click', '.traineeDeActive', function (e) {
                e.preventDefault();
                var id = $(this).data('id');
                var URL = "{{ route('traineeDeActive', 'x') }}";
                var new_url = URL.replace('x', id);
                $.ajax({
                    url: new_url,
                    dataType: "json",
                    type: 'GET',
                    success: function (html) {
                        table.DataTable().ajax.reload();
                    }
                });
            });
            table.on('click', '.traineeActive', function (e) {
                e.preventDefault();
                var id = $(this).data('id');
                var URL = "{{ route('traineeActive', 'x') }}";
                var new_url = URL.replace('x', id);
                $.ajax({
                    url: new_url,
                    dataType: "json",
                    type: 'GET',
                    success: function (html) {
                        table.DataTable().ajax.reload();
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
                            <h3 class="card-label">Trainees List </h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <!--begin: Datatable-->
                        <table class="table table-separate table-head-custom table-checkable trainees_datatable"
                               id="kt_datatable">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Degree</th>
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

