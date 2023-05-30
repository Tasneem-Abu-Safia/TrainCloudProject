@extends('Dashboard.master')

@section('title')
    Add Task
@endsection

@section('Page-title')
    Task
@endsection

@section('js')
    <script>
        $(document).ready(function () {
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
                <form method="POST" action="{{ route('tasks.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label for="course">Course<span class="text-danger">*</span></label>
                            <select class="form-control {{ $errors->has('course') ? 'is-invalid' : '' }}" name="course_id"
                                    id="course_id" required>
                                <option value="">Select Course</option>
                                @foreach($courses as $course)
                                    <option value="{{ $course->id }}">{{ $course->name }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('course'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('course') }}
                                </div>
                            @endif
                        </div>
                        <div class="col-lg-6">
                            <label for="title">Title<span class="text-danger">*</span></label>
                            <input type="text" class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}"
                                   name="title" id="title" value="{{ old('title') }}" required
                                   placeholder="Enter Title">
                            @if($errors->has('title'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('title') }}
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row">

                        <div class="col-lg-6">
                            <label for="start_date">Start Date<span class="text-danger">*</span></label>
                            <input type="date" class="form-control {{ $errors->has('start_date') ? 'is-invalid' : '' }}"
                                   name="start_date" id="start_date" value="{{ old('start_date') }}" required
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
                                   name="end_date" id="end_date" value="{{ old('end_date') }}" required
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
                            <label for="description">Description<span class="text-danger">*</span></label>
                            <textarea class="form-control" name="description" rows="2" required
                                      placeholder="Type a Description">{{ old('description') }}</textarea>
                        </div>
                        <div class="col-lg-6">
                            <label for="mark">Mark</label>
                            <input type="number" class="form-control {{ $errors->has('mark') ? 'is-invalid' : '' }}"
                                   name="mark" id="mark" min="0" value="{{ old('mark') }}" placeholder="Enter Mark">
                            @if($errors->has('mark'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('mark') }}
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label for="file">File</label>
                            <input type="file" class="form-control-file {{ $errors->has('file') ? 'is-invalid' : '' }}"
                                   name="file" id="file">
                            @if($errors->has('file'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('file') }}
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-lg-6">
                                <button type="submit" class="btn btn-primary">
                                    <span class="indicator-label">Save</span>
                                </button>
                                <input type="reset" value="Reset" class="btn btn-white me-3">
                                <a type="button" href="{{ route('tasks.index') }}" class="btn btn-white me-3">Back</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
