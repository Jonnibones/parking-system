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
	</style>
</head>
<body>
	<h1>Recibo de Serviço</h1>
	<table>
		<thead>
			<tr>
                <th>Motorista</th>
				<th>Data/Horário entrada</th>
				<th>N° vaga</th>
			</tr>
		</thead>
		<tbody>
			<tr>
                <td>{{ $service['driver_name'] }}</td>
				<td>{{ $service['entry_time'] }}</td>
				<td>{{ $service['service_code'] }}</td>
			</tr>
		</tbody>
	</table>
</body>
</html>
