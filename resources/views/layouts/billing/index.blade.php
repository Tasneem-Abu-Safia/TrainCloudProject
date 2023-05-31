@extends('Dashboard.master')
@section('title')
    Billings
@endsection
@section('subTitle')
    Billings
@endsection

@section('Page-title')
    Billings
@endsection

@section('js')
    <script type="text/javascript">
        $("#msg").show().delay(3000).fadeOut();

        $(function () {
            let modalDelete = $('#deleteModal');
            var table = $('.billings_datatable');
            table.DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('billings.index') }}",
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'trainee.user.name', name: 'trainee.user.name'},
                    {data: 'amount_due', name: 'amount_due'},
                    {data: 'payment_status', name: 'payment_status'},
                    {data: 'payment_date', name: 'payment_date'},
                    {data: 'visa', name: 'visa'},
                    {data: 'cvc', name: 'cvc'},
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
                    var URL = "{{ route('billings.destroy', 'x') }}";
                    return URL.replace('x', id);
                });

            });

            $('#deleteModal #cancelModal').on('click', function (e) {
                modalDelete.modal('hide');
            });


            table.on('click', '.billingDeActive', function (e) {
                e.preventDefault();
                var id = $(this).data('id');
                var URL = "{{ route('billingDeActive', 'x') }}";
                var new_url = URL.replace('x', id);
                $.ajax({
                    url: new_url,
                    dataType: "json",
                    type: 'GET',
                    success: function (html) {
                        console.log(html)
                        table.DataTable().ajax.reload();
                    }

                });
            });
            table.on('click', '.billingActive', function (e) {
                e.preventDefault();
                var id = $(this).data('id');
                var URL = "{{ route('billingActive', 'x') }}";
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
                            <h3 class="card-label">Billings List </h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <!--begin: Datatable-->
                        <table class="table table-separate table-head-custom table-checkable billings_datatable"
                               id="kt_datatable">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Payment Date</th>
                                <th>Visa</th>
                                <th>CVC</th>
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

