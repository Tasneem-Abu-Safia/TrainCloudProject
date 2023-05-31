@extends('Dashboard.master')
@section('title')
    My Courses
@endsection
@section('subTitle')
    My Courses
@endsection

@section('Page-title')
    My Courses
@endsection

@section('js')
    <script>
        $(document).ready(function () {
            $('#task-table').DataTable();
        });
    </script>
@endsection
@section('content')

    <div class="flex-lg-row-fluid ms-lg-10">
        <!--begin::Card-->
        <div class="card card-flush mb-6 mb-xl-9">
            <div class="card-body pt-0">
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
                <div class="row">
                    <div class="col-md-6">
                        <div class="card card-custom">
                            <div class="card-header">
                                <h3 class="card-title">Course Details</h3>
                            </div>
                            <div class="card-body">
                                <ul class="list-group">
                                    <li class="list-group-item"><strong>Name:</strong> {{ $course->name }}</li>
                                    <li class="list-group-item"><strong>Course
                                            Number:</strong> {{ $course->course_num }}</li>
                                    <li class="list-group-item"><strong>Description:</strong> {{ $course->desc }}</li>
                                    <li class="list-group-item"><strong>Field:</strong> {{ $course->field->name }}</li>
                                    <li class="list-group-item">
                                        <strong>Duration:</strong> {{ $course->duration }} {{ $course->duration_unit }}
                                    </li>
                                    <li class="list-group-item"><strong>Location:</strong> {{ $course->location }}</li>
                                    <li class="list-group-item"><strong>Start Date:</strong> {{ $course->start_date }}
                                    </li>
                                    <li class="list-group-item"><strong>End Date:</strong> {{ $course->end_date }}</li>
                                    <li class="list-group-item"><strong>Fees:</strong> {{ $course->fees }}</li>
                                    <li class="list-group-item"><strong>Capacity:</strong> {{ $course->capacity }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card card-custom">
                            <div class="card-header">
                                <h3 class="card-title">Advisor Details</h3>
                            </div>
                            <div class="card-body">
                                <ul class="list-group">
                                    <li class="list-group-item">
                                        <strong>Name:</strong> {{ $course->advisor->user->name }}</li>
                                    <li class="list-group-item"><strong>Phone:</strong> {{ $course->advisor->phone }}
                                    </li>
                                    <li class="list-group-item">
                                        <strong>Address:</strong> {{ $course->advisor->address }}</li>
                                    <li class="list-group-item"><strong>Degree:</strong> {{ $course->advisor->degree }}
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card card-custom mt-6">
                    <div class="card-header">
                        <h3 class="card-title">Tasks</h3>
                    </div>
                    <div class="card-body">
                        <table id="task-table" class="table">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Task Mark</th>
                                <th>File</th>
                                <th>Submission Mark</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($tasks as $task)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $task->title }}</td>
                                    <td>{{ $task->description }}</td>
                                    <td>{{ $task->start_date }}</td>
                                    <td>{{ $task->end_date }}</td>
                                    <td>{{ $task->mark }}</td>
                                    <td>{!! $task->file ? '<a href="' . $task->file . '" class="btn btn-light-primary" target="_blank">Show File</a>' : 'No files uploaded' !!}</td>
                                    <td>
                                        @php
                                            $submission = $task->submissions->where('trainee_id', Auth::user()->trainee->id)->first();
                                        @endphp
                                        @if ($submission)
                                            {{ $submission->mark ?? 'Not graded' }}
                                        @else
                                            <p>No submission</p>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($submission && $submission->file && !$submission->mark)
                                            <button class="btn btn-primary" data-toggle="modal"
                                                    data-target="#uploadModal{{ $task->id }}">Update
                                            </button>
                                        @elseif (!$submission || (!$submission->file && !$submission->mark))
                                            <button class="btn btn-primary" data-toggle="modal"
                                                    data-target="#uploadModal{{ $task->id }}">Upload File
                                            </button>
                                    @endif
                                    <!-- Upload Modal -->
                                        <div class="modal fade" id="uploadModal{{ $task->id }}" tabindex="-1"
                                             role="dialog" aria-labelledby="uploadModal{{ $task->id }}Label"
                                             aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title"
                                                            id="uploadModal{{ $task->id }}Label">Upload File for
                                                            Task: {{ $task->title }}</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <!-- File Upload Form -->
                                                        <form
                                                            action="{{ route('trainee.submitTask', ['taskId' => $task->id]) }}"
                                                            method="POST" enctype="multipart/form-data">
                                                            @csrf
                                                            <div class="form-group">
                                                                <label for="file">File:</label>
                                                                <input type="file" class="form-control" id="file"
                                                                       name="file" required>
                                                            </div>
                                                            <button type="submit" class="btn btn-primary">Submit
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
