<!-- SET CSRF TOKEN  -->
<meta name="csrf-token" content="{{ csrf_token() }}">


<script>
    //Datatables initialisation 
    $(document).ready(function() {
        $('#table_sep_services').DataTable();
    });
</script>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Serviços de hoje</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Serviços de hoje</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Main content -->
    <section class="content">
        <div style="padding: 20px;" class="container-fluid">

            <!-- Table -->
            <h4>Serviços de hoje</h4>
            <div style="margin-top:40px; overflow:scroll" class="container">
                <table class="table table-striped" id="table_sep_services">
                    <thead>
                        <tr>
                            <th style="text-align: center;">ID serviço</th>
                            <th style="text-align: center;">Tipo serviço</th>
                            <th style="text-align: center;">Vaga</th>
                            <th style="text-align: center;">Código do serviço</th>
                            <th style="text-align: center;">Nome motorista</th>
                            <th style="text-align: center;">Telefone motorista</th>
                            <th style="text-align: center;">N° habilitação</th>
                            <th style="text-align: center;">Placa do carro</th>
                            <th style="text-align: center;">Marca</th>
                            <th style="text-align: center;">Modelo</th>
                            <th style="text-align: center;">Cor</th>
                            <th style="text-align: center;">Data/Horário de entrada</th>
                            <th style="text-align: center;">Data/Horário de saída</th>
                            <th style="text-align: center;">Valor</th>
                            <th style="text-align: center;">Status</th>
                            <th style="text-align: center;">Usuário</th>
                            <th style="text-align: center;">Finalizar serviço</th>
                            <th style="text-align: center;">Gerar recibo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($contents['services'] as $service)
                            @if (trim($service->status) == 'Finalizado')
                                @php
                                    $color = '#98FB98';
                                @endphp
                            @else
                                @php
                                    $color = '';
                                @endphp
                            @endif

                            <tr style="background-color: {{ $color }};">
                                <td data-id="td_id_service" style="text-align: center;" id="id_service">
                                    {{ $service->id }}</td>
                                <td data-id="td_service_type">{{ $service->service_type }}</td>
                                <td data-id="td_space" style="text-align: center;">
                                    {{ $service->space_number . ' - ' . $service->space_description }}</td>
                                <td data-id="td_service_code" style="text-align: center;">{{ $service->service_code }}
                                </td>
                                <td data-id="td_driver_name" style="text-align: center;">{{ $service->driver_name }}
                                </td>
                                <td data-id="td_driver_phone_number" style="text-align: center;">
                                    {{ $service->driver_phone_number }}</td>
                                <td data-id="td_driving_license_number" style="text-align: center;">
                                    {{ $service->driving_license_number }}</td>
                                <td data-id="td_license_plate_number" style="text-align: center;">
                                    {{ $service->license_plate_number }}</td>
                                <td data-id="td_vehicle_brand" style="text-align: center;">
                                    {{ $service->vehicle_brand }}</td>
                                <td data-id="td_vehicle_model" style="text-align: center;">
                                    {{ $service->vehicle_model }}</td>
                                <td data-id="td_vehicle_color" style="text-align: center;">
                                    {{ $service->vehicle_color }}</td>
                                <td data-id="td_entry_time" style="text-align: center;" name="entry_times">
                                    {{ date('d-m-Y H:i:s', strtotime($service->entry_time)) }}</td>
                                <td data-id="td_departure_time" style="text-align: center;" name="departure_times">
                                    @if ($service->departure_time)
                                        {{ date('d-m-Y H:i:s', strtotime($service->departure_time)) }}
                                    @else
                                        --:--
                                    @endif
                                </td>
                                <td data-id="td_value" style="text-align: center;">
                                    @if ($service->value)
                                        {{ $service->value }}
                                    @else
                                        --:--
                                    @endif
                                </td>
                                <td data-id="td_status" style="text-align: center;">
                                    @if ($service->status == 'Em andamento')
                                        {{ $service->status }}<br>
                                        <iframe src="https://giphy.com/embed/l3q2IYN87QjIg51kc" width="30"
                                            height="30" frameBorder="0" class="giphy-embed" allowFullScreen></iframe>
                                        <p><a href="#"></a></p>
                                    @else
                                        {{ $service->status }}<br>
                                        <i style="color:#00FF00" class="ion-checkmark-circled"></i>
                                    @endif
                                </td>
                                <td data-id="td_user_name" style="text-align: center;">{{ $service->user_name }}</td>
                                <td data-id="td_button_service" style="text-align: center;">
                                    @if ($service->status == 'Em andamento')
                                        <button name="btns_finish" class="ladda-button" data-size="s"
                                            data-color="green" data-style="zoom-in">Finalizar serviço</button>
                                    @else
                                        <button name="btns_finish" title="Serviço finalizado" disabled
                                            class="disabled btn btn-success">Serviço finalizado</button>
                                    @endif
                                </td>
                                <td data-id="td_button_receipt" style="text-align: center;"><button
                                        name="btns_generate_receipt" data-size="s" class="ladda-button"
                                        data-color="purple" data-style="zoom-in">Gerar
                                        recibo</button></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>

<script>
    //MÉTODO REPONSÁVEL POR FINALIZAR SERVIÇO
    var btns_finish = document.getElementsByName('btns_finish');
    btns_finish.forEach(btn_finish => {
        btn_finish.addEventListener('click', function() {
            if (confirm('Tem certeza que deseja finalizar este serviço?')) {

                let btn_ladda = Ladda.create(btn_finish);
                btn_ladda.start();


                let tr = btn_finish.parentElement.parentElement;

                let id_service = tr.querySelector('td[data-id="td_id_service"]').innerHTML;

                let departure_time = tr.querySelector('td[data-id="td_departure_time"]');
                let value = tr.querySelector('td[data-id="td_value"]');
                let status = tr.querySelector('td[data-id="td_status"]');

                var csrf_token = $('meta[name="csrf-token"]').attr('content');
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': csrf_token
                    }
                });

                $.post(
                    "{{ route('finish_service') }}", {
                        id_service: id_service
                    },
                    function(data) {
                        departure_time.innerHTML = data.departure_time;
                        value.innerHTML = data.value + 'R$';
                        status.innerHTML = data.status +
                            '<br><i style="color:#00FF00" class="ion-checkmark-circled"></i>';
                        btn_finish.innerHTML = "Serviço finalizado";
                        btn_finish.className = "disabled btn btn-success";
                        btn_finish.title = "Serviço finalizado";
                        btn_finish.disabled = true;
                        tr.style.backgroundColor = '#98FB98';
                        btn_ladda.stop();
                        alert('Serviço finalizado.')
                    });
            }
        });
    });

    //MÉTODO REPONSÁVEL POR FAZER DOWNLOAD DO RECIBO EM PDF
    var btns_generate_receipt = document.getElementsByName('btns_generate_receipt');
    btns_generate_receipt.forEach(btn_generate_receipt => {
        btn_generate_receipt.addEventListener('click', function() {
            if (confirm('Tem certeza que deseja gerar o recibo para este serviço?')) {

                let btn_ladda = Ladda.create(btn_generate_receipt);
                btn_ladda.start();

                let tr = btn_generate_receipt.parentElement.parentElement;

                let id_service = tr.querySelector('td[data-id="td_id_service"]').innerHTML;

                var csrf_token = $('meta[name="csrf-token"]').attr('content');
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': csrf_token
                    }
                });

                $.post(
                    "{{ route('generate_receipt') }}", {
                        id_service: id_service,
                    },
                    function(data) {
                        var byteCharacters = atob(data);
                        var byteNumbers = new Array(byteCharacters.length);
                        for (var i = 0; i < byteCharacters.length; i++) {
                            byteNumbers[i] = byteCharacters.charCodeAt(i);
                        }
                        var byteArray = new Uint8Array(byteNumbers);
                        var blob = new Blob([byteArray], {
                            type: 'application/pdf'
                        });
                        var link = document.createElement('a');
                        link.href = window.URL.createObjectURL(blob);
                        link.download = 'recibo-servico-' + id_service + '.pdf';
                        link.click();
                        btn_ladda.stop();
                        alert('Recibo gerado.');

                    });
            }
        });
    });
</script>
