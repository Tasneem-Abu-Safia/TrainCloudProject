@extends('Dashboard.master')

@section('title')
    Course Details
@endsection
@section('Page-title')
    Course Details
@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Course Details</div>

                    <div class="card-body">
                        <form>
                            <div class="form-group">
                                <label for="name">Name:</label>
                                <input type="text" class="form-control" id="name" value="{{ $course->name }}" disabled>
                            </div>

                            <div class="form-group">
                                <label for="desc">Description:</label>
                                <textarea class="form-control" id="desc" rows="4"
                                          disabled>{{ $course->desc }}</textarea>
                            </div>

                            <div class="form-group">
                                <label for="location">Location:</label>
                                <input type="text" class="form-control" id="location" value="{{ $course->location }}"
                                       disabled>
                            </div>

                            <div class="form-group">
                                <label for="course_num">Course Number:</label>
                                <input type="text" class="form-control" id="course_num"
                                       value="{{ $course->course_num }}" disabled>
                            </div>

                            <div class="form-group">
                                <label for="duration">Duration:</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="duration"
                                           value="{{ $course->duration }}" disabled>
                                    <select class="form-control" id="duration_unit" disabled>
                                        <option value="">Select Duration Unit</option>
                                        <option value="days" {{ $course->duration_unit == 'days' ? 'selected' : '' }}>
                                            Days
                                        </option>
                                        <option value="weeks" {{ $course->duration_unit == 'weeks' ? 'selected' : '' }}>
                                            Weeks
                                        </option>
                                        <option
                                            value="months" {{ $course->duration_unit == 'months' ? 'selected' : '' }}>
                                            Months
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="start_date">Start Date:</label>
                                <input type="date" class="form-control" id="start_date"
                                       value="{{ $course->start_date }}" disabled>
                            </div>

                            <div class="form-group">
                                <label for="end_date">End Date:</label>
                                <input type="date" class="form-control" id="end_date" value="{{ $course->end_date }}"
                                       disabled>
                            </div>

                            <div class="form-group">
                                <label for="fees">Fees:</label>
                                <input type="number" class="form-control" id="fees" value="{{ $course->fees }}"
                                       disabled>
                            </div>

                            <div class="form-group">
                                <label for="capacity">Capacity:</label>
                                <input type="number" class="form-control" id="capacity" value="{{ $course->capacity }}"
                                       disabled>
                            </div>

                            <div class="form-group">
                                <a href="{{ route('courses.edit', $course->id) }}" class="btn btn-primary">
                                    Edit
                                </a>
                                <a href="{{ route('courses.index') }}" class="btn btn-secondary">
                                    Back
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>@endsection
