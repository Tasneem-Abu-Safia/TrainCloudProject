<!-- register.blade.php -->

<!DOCTYPE html>
<html lang="en">

<head>
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-QN9K6GZ09J"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-QN9K6GZ09J');
</script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{asset('admin/assets/media/logos/LOGO4.png')}}">
    <title>
        Register Page | Train</title>
    <!-- Font Awesome -->
    <link
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
        rel="stylesheet"
    />
    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap"
        rel="stylesheet"
    />
    <!-- MDB -->
    <link
        href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.3.0/mdb.min.css"
        rel="stylesheet"
    />

    <style>
        .container {
            max-width: 600px;
            margin: 0 auto;
            margin-top: 100px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .login-link {
            text-align: right;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
<div class="container">
    <div class="login-link">
        <a href="{{ route('login') }}">Back to Login</a>
    </div>

    <!-- Pills navs -->
    <ul class="nav nav-pills nav-justified mb-3" id="ex1" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link active" id="tab-trainee" data-mdb-toggle="pill" href="#pills-trainee" role="tab"
               aria-controls="pills-trainee" aria-selected="true">Trainee</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="tab-advisor" data-mdb-toggle="pill" href="#pills-advisor" role="tab"
               aria-controls="pills-advisor" aria-selected="false">Advisor</a>
        </li>
    </ul>
    <!-- Pills navs -->
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
<!-- Pills content -->
    <div class="tab-content">
        <div class="tab-pane fade show active" id="pills-trainee" role="tabpanel" aria-labelledby="tab-trainee">
            <form class="form" id="trainee-register-form" method="POST" action="{{ route('postTrainee') }}"
                  enctype="multipart/form-data">
            @csrf
            <!-- Name input -->
                <div class="form-outline mb-4">
                    <input type="text" id="traineeName"
                           class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                           value="{{ old('name', '') }}"
                           name="name" required>
                    <label class="form-label" for="traineeName">Name</label>
                    @if($errors->has('name'))
                        <div class="invalid-feedback">
                            {{ $errors->first('name') }}
                        </div>
                    @endif
                </div>
                <!-- Email input -->
                <div class="form-outline mb-4">
                    <input type="email" id="traineeEmail"
                           class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                           value="{{ old('email', '') }}"
                           name="email" required>
                    <label class="form-label" for="traineeEmail">Email</label>
                    @if($errors->has('email'))
                        <div class="invalid-feedback">
                            {{ $errors->first('email') }}
                        </div>
                    @endif
                </div>
                <!-- Password input -->
                <div class="form-outline mb-4">
                    <input type="password" id="traineePassword"
                           class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                           value="{{ old('password', '') }}" name="password" required>
                    <label class="form-label" for="traineePassword">Password</label>
                    @if($errors->has('password'))
                        <div class="invalid-feedback">
                            {{ $errors->first('password') }}
                        </div>
                    @endif
                </div>
                <!-- Phone input -->
                <div class="form-outline mb-4">
                    <input type="text" id="traineePhone"
                           class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}"
                           value="{{ old('phone', '') }}" name="phone" required>
                    <label class="form-label" for="traineePhone">Phone</label>
                    @if($errors->has('phone'))
                        <div class="invalid-feedback">
                            {{ $errors->first('phone') }}
                        </div>
                    @endif
                </div>
                <!-- Address input -->
                <div class="form-outline mb-4">
                    <input type="text" id="traineeAddress"
                           class="form-control {{ $errors->has('address') ? 'is-invalid' : '' }}"
                           value="{{ old('address', '') }}" name="address" required>
                    <label class="form-label" for="traineeAddress">Address</label>
                    @if($errors->has('address'))
                        <div class="invalid-feedback">
                            {{ $errors->first('address') }}
                        </div>
                    @endif
                </div>
                <!-- Select Degree Education -->
                <div class="form-outline mb-4">
                    <select id="traineeDegree" class="form-select  {{ $errors->has('degree') ? 'is-invalid' : '' }}"
                            value="{{ old('degree', '') }}" name="degree" required>
                        <option value="" selected disabled>Select Degree Education</option>
                        <option value="bachelor">Bachelor's Degree</option>
                        <option value="master">Master's Degree</option>
                        <option value="phd">PhD</option>
                    </select>
                    @if($errors->has('degree'))
                        <div class="invalid-feedback">
                            {{ $errors->first('degree') }}
                        </div>
                    @endif
                </div>
                <div class="form-outline mb-4">
                    <select id="traineeFields" class="form-select" name="fields[]" multiple required>
                        <option value="" selected disabled>Select Fields</option>
                        @foreach($fields as $field)
                            <option value="{{ $field->id }}">{{ $field->name }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('fields'))
                        <div class="invalid-feedback">
                            {{ $errors->first('fields') }}
                        </div>
                    @endif
                </div>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="inputGroupFileAddon01">Upload CV</span>
                    </div>
                    <div class="custom-file">
                        <input type="file" id="traineeFiles" placeholder="CV"
                               class="form-control {{ $errors->has('file') ? 'is-invalid' : '' }}"
                               name="file" required
                               aria-describedby="inputGroupFileAddon01">
                        @if($errors->has('file'))
                            <div class="invalid-feedback">
                                {{ $errors->first('file') }}
                            </div>
                        @endif
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-block mb-3">Sign up</button>
            </form>
        </div>
        <div class="tab-pane fade" id="pills-advisor" role="tabpanel" aria-labelledby="tab-advisor">
            <form class="form" id="advisor-register-form" method="POST" action="{{ route('postAdvisor') }}"
                  enctype="multipart/form-data">
            @csrf
            <!-- Name input -->
                <div class="form-outline mb-4">
                    <input type="text" id="advisorName"
                           class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                           value="{{ old('name', '') }}" name="name" required>
                    <label class="form-label" for="advisorName">Name</label>
                    @if($errors->has('name'))
                        <div class="invalid-feedback">
                            {{ $errors->first('name') }}
                        </div>
                    @endif
                </div>
                <!-- Email input -->
                <div class="form-outline mb-4">
                    <input type="email" id="advisorEmail"
                           class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                           value="{{ old('email', '') }}" name="email" required>
                    <label class="form-label" for="advisorEmail">Email</label>
                    @if($errors->has('email'))
                        <div class="invalid-feedback">
                            {{ $errors->first('email') }}
                        </div>
                    @endif
                </div>
                <!-- Password input -->
                <div class="form-outline mb-4">
                    <input type="password" id="advisorPassword"
                           class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                           value="{{ old('password', '') }}" name="password" required>
                    <label class="form-label" for="advisorPassword">Password</label>
                    @if($errors->has('password'))
                        <div class="invalid-feedback">
                            {{ $errors->first('password') }}
                        </div>
                    @endif
                </div>
                <!-- Phone input -->
                <div class="form-outline mb-4">
                    <input type="text" id="advisorPhone"
                           class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}"
                           value="{{ old('phone', '') }}" name="phone" required>
                    <label class="form-label" for="advisorPhone">Phone</label>
                    @if($errors->has('phone'))
                        <div class="invalid-feedback">
                            {{ $errors->first('phone') }}
                        </div>
                    @endif
                </div>
                <!-- Address input -->
                <div class="form-outline mb-4">
                    <input type="text" id="advisorAddress"
                           class="form-control {{ $errors->has('address') ? 'is-invalid' : '' }}"
                           value="{{ old('address', '') }}" name="address" required>
                    <label class="form-label" for="advisorAddress">Address</label>
                    @if($errors->has('address'))
                        <div class="invalid-feedback">
                            {{ $errors->first('address') }}
                        </div>
                    @endif
                </div>
                <!-- Select Degree Education -->
                <div class="form-outline mb-4">
                    <select id="advisorDegree" class="form-select  {{ $errors->has('degree') ? 'is-invalid' : '' }}"
                            name="degree" required>
                        <option value="" selected disabled>Select Degree Education</option>
                        <option value="bachelor">Bachelor's Degree</option>
                        <option value="master">Master's Degree</option>
                        <option value="phd">PhD</option>
                    </select>
                    @if($errors->has('degree'))
                        <div class="invalid-feedback">
                            {{ $errors->first('degree') }}
                        </div>
                    @endif
                </div>
                <div class="form-outline mb-4">
                    <select id="advisorFields" class="form-select" name="fields[]" multiple required>
                        <option value="" selected disabled>Select Fields</option>
                        @foreach($fields as $field)
                            <option value="{{ $field->id }}">{{ $field->name }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('fields'))
                        <div class="invalid-feedback">
                            {{ $errors->first('fields') }}
                        </div>
                    @endif
                </div>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="inputGroupFileAddon01">Upload CV</span>
                    </div>
                    <div class="custom-file">
                        <input type="file" id="advisorFiles" placeholder="CV"
                               class="form-control {{ $errors->has('file') ? 'is-invalid' : '' }}"
                               name="file" required
                               aria-describedby="inputGroupFileAddon01">
                        @if($errors->has('file'))
                            <div class="invalid-feedback">
                                {{ $errors->first('file') }}
                            </div>
                        @endif
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-block mb-3">Sign up</button>
            </form>
        </div>
    </div>

    <!-- Pills content -->
</div>
<!-- MDB -->
<script
    type="text/javascript"
    src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.3.0/mdb.min.js"
></script>
</body>

</html>
