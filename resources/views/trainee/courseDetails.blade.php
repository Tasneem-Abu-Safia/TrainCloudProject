@extends('Dashboard.master')

@section('title')
    Course Details
@endsection
@section('Page-title')
    Course Details
@endsection
@section('js')
    <script>
        $("#msg").show().delay(3000).fadeOut();
    </script>

@endsection
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">

                    <div class="card-header">Course Details</div>
                    @if(session()->has('error'))
                        <div class="alert alert-danger" id="msg">
                            {{ session()->get('error') }}
                        </div>
                    @endif
                    @if(session()->has('success'))
                        <div class="alert alert-success" id="msg">
                            {{ session()->get('success') }}
                        </div>
                    @endif
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">Name:</label>
                            <input type="text" class="form-control" id="name" value="{{ $course->name }}"
                                   disabled>
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
                            <label for="field">Field:</label>
                            <input type="text" class="form-control" id="field" value="{{ $course->field->name }}"
                                   disabled>
                        </div>
                        <div class="form-group">
                            <label for="advisor">Advisor:</label>
                            <input type="text" class="form-control" id="advisor"
                                   value="{{ $course->advisor->user->name }}"
                                   disabled>
                        </div>

                        <div class="form-group">
                            <label for="advisor">Advisor Email:</label>
                            <input type="text" class="form-control" id="advisor"
                                   value="{{ $course->advisor->user->email }}"
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
                            <label for="capacity">Free Capacity:</label>
                            <input type="number" class="form-control" id="capacity"
                                   value="{{ $course->capacity - $course->num_trainee }}"
                                   disabled>
                        </div>

                        <div class="form-group d-flex justify-content">
                            @if($course->num_trainee < $course->capacity)
                                <form id='joinForm' action="{{ route('courses.enroll', $course->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-success mr-2">Join Course</button>
                                </form>
                            @else
                                <p class="text-danger">Course is full. Cannot enroll.</p>
                            @endif
                            <a href="{{ route('getAllCourses') }}" class="btn btn-secondary">
                                Back
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
