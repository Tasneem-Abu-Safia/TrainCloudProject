@extends('Dashboard.master')

@section('title')
    Edit Course
@endsection
@section('Page-title')
    Course
@endsection
@section('js')
    <script>
        $(document).ready(function () {
            // Cache the field and advisor select elements
            var $fieldSelect = $('#field_id');
            var $advisorSelect = $('#advisor_id');

            // Function to fetch advisors based on the selected field
            function fetchAdvisors(fieldId) {
                $.ajax({
                    url: '/advisor-fields',  // Replace with your actual endpoint to fetch advisors
                    type: 'GET',
                    data: {
                        field_id: fieldId
                    },
                    success: function (response) {
                        console.log(response.advisors)
                        // Clear the advisor select options
                        $advisorSelect.empty();

                        // Add a default option
                        $advisorSelect.append($('<option>', {
                            value: '',
                            text: 'Select Advisor'
                        }));

                        // Add the fetched advisors as options
                        $.each(response.advisors, function (index, advisor) {
                            $advisorSelect.append($('<option>', {
                                value: advisor.id,
                                text: advisor.user.name
                            }));
                        });
                    },
                    error: function (xhr) {
                        // Handle the error if any
                        console.log(xhr.responseText);
                    }
                });
            }

            // Event handler for field select change
            $fieldSelect.on('change', function () {
                var selectedField = $(this).val();

                // Fetch advisors based on the selected field
                fetchAdvisors(selectedField);
            });

            // Assuming you have assigned appropriate IDs to your start and end date inputs
            var $startDateInput = $('#start_date');
            var $endDateInput = $('#end_date');

            // Add event listener to start date input
            $startDateInput.on('change', function () {
                var startDate = $startDateInput.val();

                // Set min attribute of end date input to the selected start date
                $endDateInput.attr('min', startDate);

                // Check if the current end date is before the new start date
                var endDate = $endDateInput.val();
                if (endDate < startDate) {
                    $endDateInput.val(startDate);
                }
            });

        });

    </script>
@endsection

@section('content')
    <div class="col-12">
        <div class="card mb-6">
            <div class="card-body">
                <form method="POST" action="{{ route("courses.update", $course) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label for="name">Course Name<span class="text-danger">*</span></label>
                            <input type="text" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                                   name="name" id="name" value="{{ $course->name }}" required
                                   placeholder="Enter Course Name">
                            @if($errors->has('name'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('name') }}
                                </div>
                            @endif
                        </div>
                        <div class="col-lg-6">
                            <label for="desc">Description</label>
                            <textarea class="form-control" name="desc" rows="2" required
                                      placeholder="Type a Description">{{ $course->desc }}</textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label for="location">Location</label>
                            <input type="text" class="form-control {{ $errors->has('location') ? 'is-invalid' : '' }}"
                                   name="location" id="location" value="{{ $course->location }}"
                                   placeholder="Enter Location">
                            @if($errors->has('location'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('location') }}
                                </div>
                            @endif
                        </div>

                        <div class="col-lg-6">
                            <label for="course_num">Course Number</label>
                            <input type="text" class="form-control {{ $errors->has('course_num') ? 'is-invalid' : '' }}"
                                   name="course_num" id="course_num" value="{{ $course->course_num }}"
                                   placeholder="Enter Course Number">
                            @if($errors->has('course_num'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('course_num') }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label for="field_id">Field<span class="text-danger">*</span></label>
                            <select class="form-control {{ $errors->has('field_id') ? 'is-invalid' : '' }}"
                                    name="field_id" id="field_id" required>
                                <option value="">Select Field</option>
                                @foreach($fields as $field)
                                    <option value="{{ $field->id }}" {{ $course->field_id == $field->id ? 'selected' : '' }}>
                                        {{ $field->name }}
                                    </option>
                                @endforeach
                            </select>
                            @if($errors->has('field_id'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('field_id') }}
                                </div>
                            @endif
                        </div>
                        <div class="col-lg-6">
                            <label for="advisor_id">Advisor<span class="text-danger">*</span></label>
                            <select class="form-control {{ $errors->has('advisor_id') ? 'is-invalid' : '' }}"
                                    name="advisor_id" id="advisor_id" required>
                                <option value="">Select Advisor</option>
                                @foreach($advisors as $advisor)
                                    <option value="{{ $advisor->id }}" {{ $course->advisor_id == $advisor->id ? 'selected' : '' }}>
                                        {{ $advisor->user->name }}
                                    </option>
                                @endforeach
                            </select>
                            @if($errors->has('advisor_id'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('advisor_id') }}
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label for="duration">Duration<span class="text-danger">*</span></label>
                            <input type="number" class="form-control {{ $errors->has('duration') ? 'is-invalid' : '' }}"
                                   name="duration" id="duration" value="{{ $course->duration }}" required
                                   placeholder="Enter Duration">
                            @if($errors->has('duration'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('duration') }}
                                </div>
                            @endif
                        </div>
                        <div class="col-lg-6">
                            <label for="duration_unit">Duration Unit<span class="text-danger">*</span></label>
                            <select class="form-control {{ $errors->has('duration_unit') ? 'is-invalid' : '' }}"
                                    name="duration_unit" id="duration_unit" required>
                                <option value="">Select Duration Unit</option>
                                <option value="days" {{ $course->duration_unit == 'days' ? 'selected' : '' }}>Days</option>
                                <option value="weeks" {{ $course->duration_unit == 'weeks' ? 'selected' : '' }}>Weeks</option>
                                <option value="months" {{ $course->duration_unit == 'months' ? 'selected' : '' }}>Months</option>
                            </select>
                            @if($errors->has('duration_unit'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('duration_unit') }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label for="start_date">Start Date<span class="text-danger">*</span></label>
                            <input type="date" class="form-control {{ $errors->has('start_date') ? 'is-invalid' : '' }}"
                                   name="start_date" id="start_date" value="{{ $course->start_date }}" required
                                   min="{{ date('Y-m-d') }}">
                            @if($errors->has('start_date'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('start_date') }}
                                </div>
                            @endif
                        </div>
                        <div class="col-lg-6">
                            <label for="end_date">End Date<span class="text-danger">*</span></label>
                            <input type="date" class="form-control {{ $errors->has('end_date') ? 'is-invalid' : '' }}"
                                   name="end_date" id="end_date" value="{{ $course->end_date }}" required
                                   min="{{ date('Y-m-d') }}">
                            @if($errors->has('end_date'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('end_date') }}
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label for="fees">Fees</label>
                            <input type="number" class="form-control {{ $errors->has('fees') ? 'is-invalid' : '' }}"
                                   name="fees" id="fees" value="{{ $course->fees }}" placeholder="Enter Fees">
                            @if($errors->has('fees'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('fees') }}
                                </div>
                            @endif
                        </div>
                        <div class="col-lg-6">
                            <label for="capacity">Capacity</label>
                            <input type="number" class="form-control {{ $errors->has('capacity') ? 'is-invalid' : '' }}"
                                   name="capacity" id="capacity" value="{{ $course->capacity }}"
                                   placeholder="Enter Capacity">
                            @if($errors->has('capacity'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('capacity') }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="row">
                            <div class="col-lg-6">
                                <button type="submit" class="btn btn-primary">
                                    <span class="indicator-label">Update</span>
                                </button>
                                <input type="reset" value="Reset" class="btn btn-white me-3">
                                <a type="button" href="{{ route('courses.index') }}" class="btn btn-secondary">
                                    <span class="indicator-label">Cancel</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
