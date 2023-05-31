@extends('Dashboard.master')
@section('title')
    Attendance for Course: {{$course->name}}
@endsection
@section('subTitle')
    Attendance for Course: {{$course->name}}
@endsection

@section('Page-title')
    Attendance for Course: {{$course->name}}
@endsection

@section('js')
    <script type="text/javascript">
        $("#msg").show().delay(3000).fadeOut();

        $(function () {
            var table = $('#attendance-table');
            table.DataTable();
        });

    </script>
@endsection
@section('content')

    <div class="flex-lg-row-fluid ms-lg-10">
        <!--begin::Card-->
        <div class="card card-flush mb-6 mb-xl-9">

            <div class="card-body pt-0">
                <div class="card card-custom">
                    <div class="card-header flex-wrap py-5">
                        <div class="card-title">
                            <h3 class="card-label"> Attendance for Course: {{$course->name}}</h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <!--begin: Datatable-->
                        <table id="attendance-table" class="table">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Trainee Name</th>
                                <th>Trainee Email</th>
                                <th>Date</th>
                                <th>Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($attendance as $record)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $record->trainee->user->name }}</td>
                                    <td>{{ $record->trainee->user->email }}</td>
                                    <td>{{ $record->date }}</td>
                                    <td>{{ $record->status }}</td>
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


