@extends('Dashboard.master')

@section('title')
    Trainee Details
@endsection

@section('Page-title')
    Trainee Details
@endsection
@section('js')
    <script>
        jQuery(document).ready(function () {
            var statusInput = $('#status');
            // Activate Trainee
            $('.activateBtn').click(function (e) {
                e.preventDefault();
                var traineeId = $(this).data('trainee-id');
                $.ajax({
                    url: '/trainee-active/' + traineeId,
                    method: 'GET',
                    success: function (response) {
                        // Update the button text and class
                        $('.activateBtn[data-trainee-id="' + traineeId + '"]')
                            .text('Deactivate')
                            .removeClass('btn-light-success activateBtn')
                            .addClass('btn-light-danger deactivateBtn');
                        $('#status').val('active').addClass('border-success').removeClass('border-danger');
                        location.reload();

                    },
                    error: function (xhr, status, error) {
                        console.log(xhr.responseText);
                    }
                });
            });

            // Deactivate Trainee
            $('.deactivateBtn').click(function (e) {
                e.preventDefault();
                var traineeId = $(this).data('trainee-id');
                $.ajax({
                    url: '/trainee-deActive/' + traineeId,
                    method: 'GET',
                    success: function (response) {
                        // Update the button text and class
                        $('.deactivateBtn[data-trainee-id="' + traineeId + '"]')
                            .text('Activate')
                            .removeClass('btn-light-danger deactivateBtn')
                            .addClass('btn-light-danger activateBtn');
                        $('#status').val('inactive').addClass('border-danger').removeClass('border-success');
                        location.reload();

                    },
                    error: function (xhr, status, error) {
                        console.log(xhr.responseText);
                    }
                });
            });
        });
    </script>
@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Trainee Details</div>

                    <div class="card-body">
                        <form>
                            <div class="form-group">
                                <label for="name">Name:</label>
                                <input type="text" class="form-control" id="name" value="{{ $trainee->user->name }}"
                                       disabled>
                            </div>

                            <div class="form-group">
                                <label for="email">Email:</label>
                                <input type="email" class="form-control" id="email" value="{{ $trainee->user->email }}"
                                       disabled>
                            </div>
                            <div class="form-group">
                                <label for="id">Unique Id:</label>
                                <input type="text" class="form-control" id="id"
                                       value="{{ $trainee->user->unique_id ?? '' }}"
                                       disabled>
                            </div>

                            <div class="form-group">
                                <label for="phone">Phone:</label>
                                <input type="text" class="form-control" id="phone" value="{{ $trainee->phone }}"
                                       disabled>
                            </div>

                            <div class="form-group">
                                <label for="address">Address:</label>
                                <input type="text" class="form-control" id="address" value="{{ $trainee->address }}"
                                       disabled>
                            </div>

                            <div class="form-group">
                                <label for="degree">Degree:</label>
                                <input type="text" class="form-control" id="degree" value="{{ $trainee->degree }}"
                                       disabled>
                            </div>
                            <div class="form-group">
                                <label for="fields">Fields:</label>
                                @if ($trainee->fields->count() > 0)
                                    <ul>
                                        @foreach ($trainee->fields as $field)
                                            <li>{{ $field->name }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p>No fields associated.</p>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="status">Status:</label>
                                <input type="text"
                                       class="form-control{{ $trainee->status === 'inactive' ? ' border-danger' : ' border-success' }}"
                                       id="status" value="{{ ucfirst($trainee->status) }}" disabled>
                            </div>

                            <div class="form-group">
                                <label for="files">Files:</label>
                                @if ($trainee->files)
                                    <a href="{{ asset($trainee->files) }}" class="btn btn-light-primary"
                                       target="_blank">Open CV</a>
                                @else
                                    <p>No files uploaded.</p>
                                @endif
                            </div>

                            <div class="form-group">
                                @if ($trainee->status === 'inactive')
                                    <a href="{{ route('traineeActive', $trainee->id) }}"
                                       class="btn btn-light-success activateBtn"
                                       data-trainee-id="{{ $trainee->id }}">Activate</a>
                                @else
                                    <a href="{{ route('traineeDeActive', $trainee->id) }}"
                                       class="btn btn-light-danger deactivateBtn"
                                       data-trainee-id="{{ $trainee->id }}">Deactivate</a>
                                @endif
                                <a href="{{ route('trainees.index') }}" class="btn btn-secondary">
                                    Back
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
