<?php $base_url = config('app.url'); ?>
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Login</title>

    <link rel="stylesheet" href="{{ asset('public/css/bootstrap.css') }}">
    <script src="{{ asset('public/js/bootstrap.js') }}"></script>
</head>

<body style="padding-top:5%">

    <div class="d-flex justify-content-center ">
        <div>
            <!--Se existirem erros na autenticação, exibirá uma mensagem-->
            @if($errors->has('message'))
                <div class="alert alert-danger">{{$errors->first('message')}}</div>
            @endif

            @if(session('success'))
                <div class="alert alert-info">{{session('success')}}</div>
            @endif

            <form action="<?php echo $base_url;?>auth", method="post">

                <!-- Token crfs -->
                {{ csrf_field() }}

                <!-- Email input -->
                <div class="form-outline mb-4 ">
                    <div style="margin: 20px">
                        <img src="{{ asset('public/images/logo.png') }}" alt="">
                    </div>
                    <h3 class="text text-center">Parking-System</h3>
                    <input name="email" type="email" id="form2Example1" class="form-control" />
                    <label class="form-label" for="form2Example1">Endereço de e-mail</label>
                </div>

                <!-- Password input -->
                <div class="form-outline mb-4">
                    <input name="password" value="45144617Jow@" type="password" id="form2Example2" class="form-control" />
                    <label class="form-label" for="form2Example2">Senha</label>
                </div>

                <!-- 2 column grid layout for inline styling -->
                <div class="row mb-4">
                    <div class="col d-flex justify-content-center">
                        <!-- Checkbox -->
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="form2Example31"
                                checked />
                            <label class="form-check-label" for="form2Example31"> Lembrar-me </label>
                        </div>
                    </div>
                </div>

                <!-- Submit button -->
                <button type="submit" class="btn btn-primary btn-block mb-4">Entrar</button>

            </form>
        </div>
    </div>
</body>

</html>
