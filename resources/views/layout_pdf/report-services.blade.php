<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Relatório de serviço</title>
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
    <h1>Relatório de serviço</h1>
    <table>
        <thead>
            <tr class="titles">
                <th>Id serviço</th>
                <th>Tipo serviço</th>
                <th>Valor do serviço</th>
                <th>Data/Hora entrada</th>
                <th>Data/Hora saída</th>
                <th>N° da vaga</th>
                <th>Operador</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $service)
                <tr style="text-align: center">
                    <td>{{ $service->id }}</td>
                    <td>{{ $service->service_type }}</td>
                    <td>{{ isset($service->value) ? 'R$'.$service->value : 'Em andamento'}}</td>
                    <td>{{ date('d-m-Y H:i:s', strtotime($service->entry_time)) }}</td>
                    <td>{{ isset($service->departure_time) ? date('d-m-Y H:i:s', strtotime($service->departure_time)) : 'Em andamento' }}</td>
                    <td>{{ $service->parking_space_number }}</td>
                    <td>{{ $service->user }}</td>
                </tr>
            @endforeach
            <tr style="text-align: center">
				<td colspan="7">
					<p style="font-weight: bold">CONTATO</p>
					Parking-system - Rua Avenue, N°000, Jardim Teste, Mauá-SP<br>
					(00)0000-0000
				</td>
			</tr>
        </tbody>
    </table>
</body>
</html>
