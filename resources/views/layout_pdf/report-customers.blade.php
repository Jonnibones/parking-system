<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Relatório de clientes</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 14px;
            line-height: 1.5;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table th, table td {
            padding: 8px;
            border: 1px solid #ccc;
        }
        .titles {
            background-color: rgb(197, 211, 215);
        }
        .logo {
            max-width: 60px;
            float: right;
            margin-top: 20px;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <img src="{{ asset('public/images/logo.png') }}" alt="Logo" class="logo">
    <h1>Relatório de clientes</h1>
    <table>
        <thead>
            <tr class="titles">
                <th>Id cliente</th>
                <th>Nome</th>
                <th>N° habilitação</th>
                <th>E-mail</th>
                <th>Telefone</th>
                <th>Endereço</th>
                <th>Gênero</th>
                <th>Idade</th>
                <th>Data cadastro</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $customer)
                <tr style="text-align: center">
                    <td>{{ $customer->id }}</td>
                    <td>{{ $customer->name }}</td>
                    <td>{{ $customer->driving_license_number }}</td>
                    <td>{{ $customer->email }}</td>
                    <td>{{ $customer->phone }}</td>
                    <td>{{ $customer->address }}</td>
                    <td>{{ $customer->gender }}</td>
                    <td>{{ $customer->age }}</td>
                    <td>{{ isset($customer->created_at) ? date('d-m-Y H:i:s', strtotime($customer->created_at)) : '--' }}</td>
                </tr>
            @endforeach
            <tr style="text-align: center">
                <td colspan="9">
                    <p style="font-weight: bold">CONTATO</p>
                    Parking-system - Rua Avenue, N°000, Jardim Teste, Mauá-SP<br>
                    (00)0000-0000
                </td>
            </tr>
        </tbody>
    </table>
</body>
</html>
