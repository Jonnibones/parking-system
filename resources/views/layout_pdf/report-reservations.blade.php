<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Relatório de reserva</title>
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
    <h1>Relatório de reservas</h1>
    <table>
        <thead>
            <tr class="titles">
                <th>Id reserva</th>
                <th>Cliente</th>
                <th>N° vaga</th>
                <th>Data/Hora reserva</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $reservation)
                <tr style="text-align: center">
                    <td>{{ $reservation->id }}</td>
                    <td>{{ $reservation->customer }}</td>
                    <td>{{ $reservation->parking_space_number }}</td>
                    <td>{{ date('d-m-Y H:i:s', strtotime($reservation->created_at)) }}</td>
                </tr>
            @endforeach
            <tr style="text-align: center">
				<td colspan="4">
					<p style="font-weight: bold">CONTATO</p>
					Parking-system - Rua Avenue, N°000, Jardim Teste, Mauá-SP<br>
					(00)0000-0000
				</td>
			</tr>
        </tbody>
    </table>
</body>
</html>
