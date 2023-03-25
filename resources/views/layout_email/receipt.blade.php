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
                <th>ID serviço</th>
				<th>Data/Horário entrada</th>
				<th>N° vaga</th>
			</tr>
		</thead>
		<tbody>
			<tr>
                <td>{{ $service->id }}</td>
				<td>{{ $service->entry_time }}</td>
				<td>{{ $service->parking_space_number }}</td>
			</tr>
		</tbody>
	</table>
</body>
</html>
