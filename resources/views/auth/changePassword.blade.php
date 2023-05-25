@extends('Dashboard.master')

@section('title')
    Change Password
@endsection
@section('Page-title')
    Change Password
@endsection

@section('js')
    <script type="text/javascript">
        $("#msg").show().delay(3000).fadeOut();
    </script>
@endsection
@section('content')
    <div class="col-12">
        <div class="card mb-6">
            @if(session()->has('msg'))
                <div class="alert alert-success" id="msg">
                    {{ session()->get('msg') }}
                </div>
            @endif
            <div class="card-body">
                <form method="POST" action="{{ route('changePassword') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label class="required" for="password">New Password:</label>
                            <input type="password" name="password" id="password" class="form-control
                                       {{ $errors->has('password') ? 'is-invalid' : '' }}"
                                   value="{{ old('password', '') }}"
                                   placeholder="Enter new password" required/>
                            @if($errors->has('password'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('password') }}
                                </div>
                            @endif

                        </div>
                        <div class="col-lg-6">
                            <label class="required" for="password_confirmation">Confirm Password:</label>
                            <input type="password" class="form-control
                                        {{ $errors->has('password_confirmation') ? 'is-invalid' : '' }}"
                                   value="{{ old('phoneNumber', '') }}"
                                   name="password_confirmation" id="password_confirmation" required
                                   placeholder="Password Confirmation"/>
                            @if($errors->has('password_confirmation'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('password_confirmation') }}
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
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
