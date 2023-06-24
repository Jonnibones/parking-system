<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página não encontrada</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

    <div style="margin-top: 50px; margin-bottom: 50px;"class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">404 - Página não encontrada</div>

                    <div class="card-body">
                        <div class="alert alert-success" role="alert">
                            <strong>Parabéns!</strong> Você descobriu uma página secreta!
                        </div>

                        <p>
                            Infelizmente, a página que você está procurando não foi encontrada.
                        </p>
                        <p>
                            Clique no botão abaixo para voltar à página principal:
                        </p>
                        <a href="{{ route('admin') }}" class="btn btn-primary">Voltar para a página principal</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <h1 class="text text-center">Parking-system</h1>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
