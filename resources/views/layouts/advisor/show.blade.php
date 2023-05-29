@extends('Dashboard.master')

@section('title')
    Advisor Details
@endsection

@section('Page-title')
    Advisor Details
@endsection

@section('js')
    <script>
        var statusInput = $('#status');

        // Activate Advisor
        $('.activateBtn').click(function (e) {
            e.preventDefault();
            var advisorId = $(this).data('advisor-id');
            $.ajax({
                url: '/advisor-active/' + advisorId,
                method: 'GET',
                success: function (response) {
                    // Update the button text and class
                    $('.activateBtn[data-advisor-id="' + advisorId + '"]')
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

        // Deactivate Advisor
        $('.deactivateBtn').click(function (e) {
            e.preventDefault();
            var advisorId = $(this).data('advisor-id');
            $.ajax({
                url: '/advisor-deActive/' + advisorId,
                method: 'GET',
                success: function (response) {
                    // Update the button text and class
                    $('.deactivateBtn[data-advisor-id="' + advisorId + '"]')
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
    </script>
@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Advisor Details</div>

                    <div class="card-body">
                        <form>
                            <div class="form-group">
                                <label for="name">Name:</label>
                                <input type="text" class="form-control" id="name" value="{{ $advisor->user->name }}"
                                       disabled>
                            </div>

                            <div class="form-group">
                                <label for="email">Email:</label>
                                <input type="email" class="form-control" id="email" value="{{ $advisor->user->email }}"
                                       disabled>
                            </div>

                            <div class="form-group">
                                <label for="phone">Phone:</label>
                                <input type="text" class="form-control" id="phone" value="{{ $advisor->phone }}"
                                       disabled>
                            </div>

                            <div class="form-group">
                                <label for="address">Address:</label>
                                <input type="text" class="form-control" id="address" value="{{ $advisor->address }}"
                                       disabled>
                            </div>

                            <div class="form-group">
                                <label for="degree">Degree:</label>
                                <input type="text" class="form-control" id="degree" value="{{ $advisor->degree }}"
                                       disabled>
                            </div>
                            <div class="form-group">
                                <label for="fields">Fields:</label>
                                @if ($advisor->fields->count() > 0)
                                    <ul>
                                        @foreach ($advisor->fields as $field)
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
                                       class="form-control{{ $advisor->status === 'inactive' ? ' border-danger' : ' border-success' }}"
                                       id="status" value="{{ ucfirst($advisor->status) }}" disabled>
                            </div>

                            <div class="form-group">
                                <label for="files">Files:</label>
                                @if ($trainee->files)
                                    <a href="{{ asset($trainee->files) }}" class="btn btn-light-primary" target="_blank">Open CV</a>
                                @else
                                    <p>No files uploaded.</p>
                                @endif
                            </div>

                            <div class="form-group">
                                @if ($advisor->status === 'inactive')
                                    <a href="{{ route('advisorActive', $advisor->id) }}"
                                       class="btn btn-light-success activateBtn"
                                       data-advisor-id="{{ $advisor->id }}">Activate</a>
                                @else
                                    <a href="{{ route('advisorDeActive', $advisor->id) }}"
                                       class="btn btn-light-danger deactivateBtn"
                                       data-advisor-id="{{ $advisor->id }}">Deactivate</a>
                                @endif
                                <a href="{{ route('advisors.index') }}" class="btn btn-secondary">
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

