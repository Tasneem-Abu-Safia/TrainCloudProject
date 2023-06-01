@extends('Dashboard.master')

@section('title')
    Update Profile
@endsection

@section('Page-title')
    Update Profile
@endsection

@section('js')
    <script type="text/javascript">
        $("#msg").show().delay(3000).fadeOut();
    </script>
@endsection

@section('content')
    <div class="col-12">
        <div class="card mb-6">
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
                <form method="POST" action="{{ route('updateProfile') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    @php
                        $user = \Illuminate\Support\Facades\Auth::user();
                         $guard = null;
                         if ($user->guard == 'trainee'){
                             $guard = $user->trainee;
                         }else if($user->guard == 'advisor'){
                             $guard = $user->advisor;
                         }
                    @endphp
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label class="required" for="name">Name:</label>
                            <input type="text" name="name" id="name" class="form-control
                                       {{ $errors->has('name') ? 'is-invalid' : '' }}"
                                   value="{{ old('name', $user->name ?? '') }}"
                                   placeholder="Enter Name" required/>
                            @if($errors->has('name'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('name') }}
                                </div>
                            @endif
                        </div>
                        <div class="col-lg-6">
                            <label class="required" for="email">Email:</label>
                            <input type="email" name="email" id="email" class="form-control
                                       {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                   value="{{ old('email', $user->email ?? '') }}"
                                   placeholder="Enter Email" required/>
                            @if($errors->has('email'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('email') }}
                                </div>
                            @endif
                        </div>

                    </div>
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label class="required" for="phone">Phone:</label>
                            <input type="text" name="phone" id="phone" class="form-control
                                       {{ $errors->has('phone') ? 'is-invalid' : '' }}"
                                   value="{{ old('phone', $guard->phone ?? '') }}"
                                   placeholder="Enter phone" required/>
                            @if($errors->has('phone'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('phone') }}
                                </div>
                            @endif
                        </div>

                        <div class="col-lg-6">
                            <label class="required" for="address">Address:</label>
                            <input type="text" class="form-control
                                        {{ $errors->has('address') ? 'is-invalid' : '' }}"
                                   value="{{ old('address', $guard->address ?? '') }}"
                                   name="address" id="address" required
                                   placeholder="Enter address"/>
                            @if($errors->has('address'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('address') }}
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label class="required" for="degree">Degree:</label>
                            <select class="form-control {{ $errors->has('degree') ? 'is-invalid' : '' }}"
                                    name="degree" id="degree" required>
                                <option
                                    value="bachelor" {{ old('degree', $guard->degree ?? '') == 'bachelor' ? 'selected' : '' }}>
                                    Bachelor's Degree
                                </option>
                                <option
                                    value="master" {{ old('degree', $guard->degree ?? '') == 'master' ? 'selected' : '' }}>
                                    Master's Degree
                                </option>
                                <option
                                    value="bachelor" {{ old('phd', $guard->degree ?? '') == 'phd' ? 'selected' : '' }}>
                                    PhD
                                </option>

                            </select>
                            @if($errors->has('degree'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('degree') }}
                                </div>
                            @endif
                        </div>
                        <div class="col-lg-6">
                            <select id="fields" class="form-control" name="fields[]" multiple required>
                                <option value="" selected disabled>Select Fields</option>
                                @foreach($fields as $field)
                                    <option value="{{ $field->id }}" {{ $guard->fields->pluck('id')->contains($field->id) ? 'selected' : '' }}>
                                        {{ $field->name }}
                                    </option>
                                @endforeach
                            </select>
                            @if($errors->has('fields'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('fields') }}
                                </div>
                            @endif
                        </div>

                    </div>
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label for="files">Files:</label>
                            @if ($guard->files)
                                <a href="{{ asset($guard->files) }}" class="btn btn-light-primary" target="_blank">Open
                                    CV</a>
                            @else
                                <p>No files uploaded.</p>
                            @endif
                        </div>
                        <div class="col-lg-6">
                            <label for="files">Files:</label>
                            <input type="file" class="form-control {{ $errors->has('files') ? 'is-invalid' : '' }}"
                                   name="files" id="files">
                            @if($errors->has('files'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('files') }}
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
