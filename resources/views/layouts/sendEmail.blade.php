@extends('Dashboard.master')

@section('title')
    Send Email
@endsection

@section('Page-title')
    Send Email
@endsection

@section('js')
    <script type="text/javascript">
        $("#msg").show().delay(3000).fadeOut();
    </script>
@endsection

@section('content')
    <div class="col-12">
        <div class="card mb-6">
            @if(session()->has('success'))
                <div class="alert alert-success" id="msg">
                    {{ session()->get('success') }}
                </div>
            @endif
            <div class="card-body">
                <form method="POST" action="{{ route('sendEmail') }}">
                    @csrf
                    <div class="form-group">
                        <label for="subject" class="required">Subject:<span class="text-danger">*</span></label>
                        <input type="text" name="subject" id="subject"
                               class="form-control {{ $errors->has('subject') ? 'is-invalid' : '' }}"
                               value="{{ old('subject') }}" placeholder="Enter the subject" required>
                        @if($errors->has('subject'))
                            <div class="invalid-feedback">
                                {{ $errors->first('subject') }}
                            </div>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="message" class="required">Message:<span class="text-danger">*</span></label>
                        <textarea name="message" id="message"
                                  class="form-control {{ $errors->has('message') ? 'is-invalid' : '' }}"
                                  placeholder="Enter your message" required>{{ old('message') }}</textarea>
                        @if($errors->has('message'))
                            <div class="invalid-feedback">
                                {{ $errors->first('message') }}
                            </div>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="course">Select Course:<span class="text-danger">*</span></label>
                        <select name="course[]" id="course" class="form-control" multiple>
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
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Send Email</button>
                        <button type="reset" class="btn btn-white">Reset</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
