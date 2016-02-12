<!DOCTYPE html>
<html lang="en">
<head>
  <title>Create Domain</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container">
  <h2>Craete Domain</h2>
  {!! Form::open(array('url' => 'super/domain/create')) !!}
  
    @if(Session::has('message'))
        <div>
            <p class="alert alert-success">{{Session::get('message')}}</p>
        </div>
    @endif
        
    @if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <div class="form-group row">        
        <label for="domain" class="col-sm-2 form-control-label">Domain*:</label>
        <div class="col-sm-10">
        <input type="text" class="form-control" id="domain" name="domain" placeholder="Enter domain name" required><span>.laravelapi.com</span>
        @if ($errors->has('domain')) <p class="help-block">{{ $errors->first('domain') }}</p> @endif
        </div>
    </div>
        
    <div class="form-group row">
        <label for="host" class="col-sm-2 form-control-label">Database Host*:</label>
        <div class="col-sm-10">
        <input type="text" class="form-control" id="host" name="host" placeholder="Enter host name" required>
        @if ($errors->has('host')) <p class="help-block">{{ $errors->first('host') }}</p> @endif
        </div>
    </div>
        
    <div class="form-group row">
        <label for="dbname" class="col-sm-2 form-control-label">Database Name*:</label>
        <div class="col-sm-10">
        <input type="text" class="form-control" id="dbname" name="dbname" placeholder="Enter databse name" required>
        @if ($errors->has('dbname')) <p class="help-block">{{ $errors->first('dbname') }}</p> @endif
        </div>
    </div>
        
    <div class="form-group row">
        <label for="username" class="col-sm-2 form-control-label">Database User Name*:</label>
        <div class="col-sm-10">
        <input type="text" class="form-control" id="username" name="username" placeholder="Enter user name" required>
        @if ($errors->has('username')) <p class="help-block">{{ $errors->first('username') }}</p> @endif
        </div>
    </div>
        
    <div class="form-group row">
        <label for="password" class="col-sm-2 form-control-label">Database password:</label>
        <div class="col-sm-10">
        <input type="password" class="form-control" id="password" name="password" placeholder="Enter password">
        @if ($errors->has('password')) <p class="help-block">{{ $errors->first('password') }}</p> @endif
        </div>
    </div>
        
    <div class="form-group row">
        <label for="password" class="col-sm-2 form-control-label">Database Prefix:</label>
        <div class="col-sm-10">
        <input type="text" class="form-control" id="prefix" name="prefix" placeholder="Enter password">
        @if ($errors->has('prefix')) <p class="help-block">{{ $errors->first('prefix') }}</p> @endif
        </div>
    </div>
        
    <button type="submit" class="btn btn-default">Create</button>
  {!! Form::close() !!}
</div>

</body>
</html>