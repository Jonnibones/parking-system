<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Recibo</title>
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
		.titles{
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
	<h1>Recibo de Serviço</h1>
	<table>
		<thead>
			<tr>
				<th>ID serviço</th>
				<th>Código do serviço</th>
				<th>Data/Horário entrada</th>
				<th>N° vaga/Descrição</th>
			</tr>
		</thead>
		<tbody>
			<tr style="text-align: center">
				<td>{{ $service->id }}</td>
				<td>{{ $service->service_code }}</td>
				<td>{{ date('d-m-Y H:i:s', strtotime($service->entry_time)) }}</td>
				<td>{{ $service->parking_space_number.' - '.$service->parking_space_description }}</td>
			</tr>
			<tr>
				<th>Nome motorista</th>
				<th>N° habilitação</th>
				<th>N° placa</th>
				<th>Marca veículo</th>
			</tr>
			<tr style="text-align: center">
				<td>{{ $service->driver_name }}</td>
				<td>{{ $service->driving_license_number }}</td>
				<td>{{ $service->license_plate_number }}</td>
				<td>{{ $service->vehicle_brand }}</td>
			</tr>
			<tr>
				<th>Modelo veículo</th>
				<th>Cor veículo</th>
				<th>Status serviço</th>
				<th>Atendente</th>
			</tr>
			<tr style="text-align: center">
				<td>{{ $service->vehicle_model }}</td>
				<td>{{ $service->vehicle_color }}</td>
				<td>{{ $service->status }}</td>
				<td>{{ $service->operator_name }}</td>
			</tr>
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
