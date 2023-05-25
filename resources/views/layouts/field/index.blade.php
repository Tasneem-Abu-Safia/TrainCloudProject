@extends('Dashboard.master')
@section('title')
    Fields
@endsection
@section('subTitle')
    Fields
@endsection

@section('Page-title')
    Fields
@endsection

@section('js')
    <script type="text/javascript">
        $("#msg").show().delay(3000).fadeOut();

        $(function () {
            let modalDelete = $('#deleteModal');
            var table = $('.fields_datatable');
            table.DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('fields.index') }}",
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'name', name: 'name'},
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
                    var URL = "{{ route('fields.destroy', 'x') }}";
                    return URL.replace('x', id);
                });

            });

            $('#deleteModal #cancelModal').on('click', function (e) {
                modalDelete.modal('hide');
            });

        });
        $(document).ready(function () {
            // Handle form submission
            $('#addFieldForm').submit(function (e) {
                e.preventDefault();

                // Retrieve field name from the input
                var fieldName = $('#fieldName').val();

                // Make an AJAX request to the controller
                $.ajax({
                    url: "{{ route('fields.store') }}",
                    type: "POST",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "name": fieldName
                    },
                    success: function (response) {
                        if (response.msg) {
                            $('.fields_datatable').DataTable().ajax.reload();
                            document.getElementById('msg').textContent = response.msg;
                            document.getElementById('msg').removeAttribute('hidden');
                            console.log(response.msg);
                            $("#msg").show().delay(3000).fadeOut();

                        } else {
                            document.getElementById('error').textContent = response.error;
                            document.getElementById('error').removeAttribute('hidden');
                            console.log(response.error);
                            $("#error").show().delay(3000).fadeOut();

                        }
                    },
                    error: function (xhr, status, error) {
                        // Handle error response, such as displaying an error message
                        console.error(xhr.responseText);
                    }
                });

                // Close the modal after submission
                $('#addFieldModal').modal('hide');
            });

            // Show the modal when the "Create Field" button is clicked
            $('.btn-success').click(function () {
                $('#addFieldModal').modal('show');
            });
        });


        $(document).ready(function () {
            // Handle edit button click event
            $('#kt_datatable').on('click', '.editField', function () {
                var fieldId = $(this).data('id');
                var fieldName = $(this).closest('tr').find('td:nth-child(2)').text();
                // Set field name in the edit form
                $('#editFieldName').val(fieldName);

                // Open the edit modal
                $('#editFieldModal').modal('show');

                // Handle edit form submission
                $('#editFieldForm').submit(function (e) {
                    e.preventDefault();

                    // Retrieve updated field name from the input
                    var updatedFieldName = $('#editFieldName').val();

                    // Make an AJAX request to update the field
                    $.ajax({
                        url: "/fields/" + fieldId,
                        type: "PUT",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "_method": "PUT",
                            "name": updatedFieldName
                        },
                        success: function (response) {
                            $('#editFieldModal').modal('hide');

                            if (response.msg) {
                                $('.fields_datatable').DataTable().ajax.reload();
                                document.getElementById('msg').textContent = response.msg;
                                document.getElementById('msg').removeAttribute('hidden');
                                console.log(response.msg);
                                $("#msg").show().delay(3000).fadeOut();

                            } else {
                                document.getElementById('error').textContent = response.error;
                                document.getElementById('error').removeAttribute('hidden');
                                console.log(response.error);
                                $("#error").show().delay(3000).fadeOut();

                            }
                        },
                        error: function (xhr, status, error) {
                            // Handle error response, such as displaying an error message
                        }
                    });
                });
            });

            // Rest of your code...
        });

    </script>
@endsection
@section('content')

    <div class="flex-lg-row-fluid ms-lg-10">
        <!--begin::Card-->
        <div class="card card-flush mb-6 mb-xl-9">

            <div class="card-body pt-0">
                <div class="alert alert-success" hidden id="msg">
                </div>
                <div class="alert alert-danger" hidden id="error"></div>
                @if(session()->has('msg'))
                    <div class="alert alert-success" id="msg">
                        {{ session()->get('msg') }}
                    </div>
                @endif
                <div class="card card-custom">

                    <div class="card-header flex-wrap py-5">
                        <div class="card-title">
                            <h3 class="card-label">Fields List </h3>
                        </div>
                        <div class="card-toolbar">
                            <div class="d-flex align-items-center position-relative my-1"
                                 data-kt-view-roles-table-toolbar="base">
                                <button type="button" class="btn btn-sm btn-light-primary er fs-6 px-8 py-4"
                                        data-bs-toggle="modal"
                                        data-bs-target="#addFieldModal">
                                    Create New Field
                                </button>

                            </div>
                        </div>

                    </div>
                    <div class="card-body">
                        <!--begin: Datatable-->
                        <table class="table table-separate table-head-custom table-checkable fields_datatable"
                               id="kt_datatable">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
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


    <div class="modal fade" id="addFieldModal" tabindex="-1" role="dialog" aria-labelledby="addFieldModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addFieldModalLabel">Create New Field</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="addFieldForm">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="fieldName">Field Name:</label>
                            <input type="text" class="form-control" id="fieldName" name="name">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="modal fade" id="editFieldModal" tabindex="-1" role="dialog" aria-labelledby="editFieldModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editFieldModalLabel">Edit Field</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="editFieldForm">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="editFieldName">Field Name:</label>
                            <input type="text" class="form-control" id="editFieldName" name="name">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
