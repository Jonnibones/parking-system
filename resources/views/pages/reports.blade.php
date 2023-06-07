<!-- SET CSRF TOKEN  -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<style>
    /* Fixed-header style 
    #div_table {
        width: 100%;
        overflow-y: auto;
        max-height: 300px;
    }
    #div_table th {
        position: sticky;
        top: 0;
        z-index: 1;
        background-color: #f2f2f2;
    }
    */
</style>

<script>
    //Datatables initialisation 
    /*
    $(document).ready(function() {
        $('#tb_customers_vehicles').DataTable({
            paginate:false,
        });
    });
    */
</script>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Relatórios</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Relatórios</li>
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

        <div style="padding: 20px; margin-bottom: 20px;" class="container-fluid">
            <!-- Small boxes (Stat box) -->
            <!-- Form-->

            <h4 >Dados relatório</h4>
            <div class="container">

                <form action="#"  method="post">
                    @csrf
                    <div style="margin-bottom: 30px;" class="row">
                        <div class="col col-md-4">
                            <label for="">Tipo</label>
                            <select data-name="selReportsData" class="form-control" id="sel_report">
                                <option selected disabled value="">Selecione uma opção</option>
                                <option value="Services">Serviços</option>
                                <option value="Spaces">Vagas</option>
                                <option value="Customers">Clientes</option>
                                <option value="Reservations">Reservas</option>
                            </select>
                        </div>
                        <div class="col col-md-3">
                            <label for="">Data inicial</label>
                            <input data-name="inpsReportsData" type="date" class="form-control" name="selInitialPeriod" id="selInitialPeriod">
                        </div>
                        <div class="col col-md-3">
                            <label for="">Data Final</label>
                            <input data-name="inpsReportsData" type="date" class="form-control" name="selFinalPeriod" id="selFinalPeriod">
                        </div>
                    </div>
                </form>

            </div>

            

            <div style="height:40px;" class="mt-4 mb-4"><!-- spacement --></div>

            <div class="container">
                <!-- Table -->
                <h4 hidden id="reportTitle"></h4>
                <div hidden style="overflow-x: scroll; overflow-y:scroll; height:400px;" id="reportDiv" class="container">
                    
                </div>

                <div style="margin-top: 20px;" class="container">
                    <div class="row">
                      <div class="col">
                        <canvas id="chartContainer1"></canvas>
                      </div>
                      <div class="col">
                        <canvas id="chartContainer2"></canvas>
                      </div>
                      <div class="col">
                        <canvas id="chartContainer3"></canvas>
                      </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
    
</div>

<script>
    const sel_report = document.querySelector("#sel_report");
    const selInitialPeriod = document.querySelector("#selInitialPeriod");
    const selFinalPeriod = document.querySelector("#selFinalPeriod");
    sel_report.addEventListener('change', function(){

        let reportTitle = document.querySelector("#reportTitle");
        let reportDiv = document.querySelector("#reportDiv");

        reportDiv.innerHTML = '';
        reportTitle.innerHTML = '';


        if(sel_report.options[sel_report.selectedIndex].value != '' && selInitialPeriod.value != '' && selFinalPeriod.value != ''){
            let type = sel_report.options[sel_report.selectedIndex].value;
            let initialPeriod = selInitialPeriod.value;
            let finalPeriod = selFinalPeriod.value;

            let csrf_token = document.querySelector('meta[name="csrf-token"]').content;

            $.ajaxSetup({
                headers:{
                    'X-CSRF-TOKEN' : csrf_token
                }
            });

            $.post(
                '{{ route("searchReport") }}',
                {
                    type: type,
                    initialPeriod: initialPeriod,
                    finalPeriod: finalPeriod
                },
                function(data){
                    if(data){
                        if(data[0].type == 'service'){

                            reportTitle.hidden = false;
                            reportTitle.innerHTML = 'Serviços';
                            
                            var tableContent = '<table class="table table-striped">' +
                                                    '<thead>' +
                                                        '<tr>' +
                                                        '<th>Id serviço</th>' +
                                                        '<th>Tipo serviço</th>' +
                                                        '<th>Valor do serviço</th>' +
                                                        '<th>Data/Hora entrada</th>' +
                                                        '<th>Data/Hora saída</th>' +
                                                        '<th>N° da vaga</th>' +
                                                        '<th>Operador</th>' +
                                                        '</tr>' +
                                                    '</thead>' +
                                                    '<tbody>';

                            data.forEach(function (service) {
                                tableContent += '<tr>' +
                                    '<td>' + service.id + '</td>' +
                                    '<td>' + service.service_type + '</td>' +
                                    '<td>' + service.value + '</td>' +
                                    '<td>' + new Date(service.entry_time).toLocaleString('pt-BR', { day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit', second: '2-digit' }) + '</td>' +
                                    '<td>' + new Date(service.departure_time).toLocaleString('pt-BR', { day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit', second: '2-digit' }) + '</td>'+
                                    '<td>' + service.parking_space_number + '</td>' +
                                    '<td>' + service.user + '</td>' +
                                    '</tr>';
                            });

                            tableContent += '</tbody>' +
                                '</table>';

                            reportDiv.innerHTML = tableContent;
                            reportDiv.hidden = false;


                            //Gráfico 1
                            var heading = document.createElement('h4');
                            heading.textContent = 'Tipo serviço';
                            var chartContainer = document.getElementById('chartContainer1');
                            chartContainer.parentNode.insertBefore(heading, chartContainer);
                            var ctx = document.getElementById('chartContainer1').getContext('2d');
                            var serviceTypes = [];
                            var serviceCounts = [];
                            var backgroundColors = [];
                            data.forEach(function (service) {
                                var typeIndex = serviceTypes.indexOf(service.service_type);

                                if (typeIndex === -1) {
                                    serviceTypes.push(service.service_type);
                                    serviceCounts.push(1);
                                } else {
                                    serviceCounts[typeIndex]++;
                                }
                            });
                            var totalServices = serviceCounts.reduce(function (a, b) {
                                return a + b;
                            }, 0);
                            var percentageData = serviceCounts.map(function (count) {
                                return (count / totalServices) * 100;
                            });
                            for (let i = 0; i < serviceTypes.length; i++) {
                                backgroundColors.push(getRandomColor());
                            }
                            function getRandomColor() {
                                var letters = '0123456789ABCDEF';
                                var color = '#';
                                for (var i = 0; i < 6; i++) {
                                    color += letters[Math.floor(Math.random() * 16)];
                                }
                                return color;
                            }
                            var myChart = new Chart(ctx, {
                                type: 'pie',
                                data: {
                                    labels: serviceTypes,
                                    datasets: [{
                                        data: percentageData,
                                        backgroundColor: backgroundColors,
                                    }],
                                },
                                options: {
                                    responsive: true,
                                    plugins: {
                                        datalabels: {
                                            formatter: function (value, context) {
                                                return value.toFixed(2) + '%';
                                            }
                                        }
                                    }
                                },
                            });

                            //Gráfico 2
                            var heading = document.createElement('h4');
                            heading.textContent = 'Vagas';
                            var chartContainer = document.getElementById('chartContainer2');
                            chartContainer.parentNode.insertBefore(heading, chartContainer);
                            var ctx = document.getElementById('chartContainer2').getContext('2d');
                            var serviceTypes = [];
                            var serviceCounts = [];
                            var backgroundColors = [];
                            data.forEach(function (service) {
                                var typeIndex = serviceTypes.indexOf(service.parking_space_number);

                                if (typeIndex === -1) {
                                    serviceTypes.push(service.parking_space_number);
                                    serviceCounts.push(1);
                                } else {
                                    serviceCounts[typeIndex]++;
                                }
                            });
                            var totalServices = serviceCounts.reduce(function (a, b) {
                                return a + b;
                            }, 0);
                            var percentageData = serviceCounts.map(function (count) {
                                return (count / totalServices) * 100;
                            });
                            for (let i = 0; i < serviceTypes.length; i++) {
                                backgroundColors.push(getRandomColor());
                            }
                            function getRandomColor() {
                                var letters = '0123456789ABCDEF';
                                var color = '#';
                                for (var i = 0; i < 6; i++) {
                                    color += letters[Math.floor(Math.random() * 16)];
                                }
                                return color;
                            }
                            var myChart = new Chart(ctx, {
                                type: 'pie',
                                data: {
                                    labels: serviceTypes,
                                    datasets: [{
                                        data: percentageData,
                                        backgroundColor: backgroundColors,
                                    }],
                                },
                                options: {
                                    responsive: true,
                                    plugins: {
                                        datalabels: {
                                            formatter: function (value, context) {
                                                return value.toFixed(2) + '%';
                                            }
                                        }
                                    }
                                },
                            });



                            //Gráfico 3
                            var heading = document.createElement('h4');
                            heading.textContent = 'Operadores';
                            var chartContainer = document.getElementById('chartContainer3');
                            chartContainer.parentNode.insertBefore(heading, chartContainer);
                            var ctx = document.getElementById('chartContainer3').getContext('2d');
                            var serviceTypes = [];
                            var serviceCounts = [];
                            var backgroundColors = [];
                            data.forEach(function (service) {
                                var typeIndex = serviceTypes.indexOf(service.user);

                                if (typeIndex === -1) {
                                    serviceTypes.push(service.user);
                                    serviceCounts.push(1);
                                } else {
                                    serviceCounts[typeIndex]++;
                                }
                            });
                            var totalServices = serviceCounts.reduce(function (a, b) {
                                return a + b;
                            }, 0);
                            var percentageData = serviceCounts.map(function (count) {
                                return (count / totalServices) * 100;
                            });
                            for (let i = 0; i < serviceTypes.length; i++) {
                                backgroundColors.push(getRandomColor());
                            }
                            function getRandomColor() {
                                var letters = '0123456789ABCDEF';
                                var color = '#';
                                for (var i = 0; i < 6; i++) {
                                    color += letters[Math.floor(Math.random() * 16)];
                                }
                                return color;
                            }
                            var myChart = new Chart(ctx, {
                                type: 'pie',
                                data: {
                                    labels: serviceTypes,
                                    datasets: [{
                                        data: percentageData,
                                        backgroundColor: backgroundColors,
                                    }],
                                },
                                options: {
                                    responsive: true,
                                    plugins: {
                                        datalabels: {
                                            formatter: function (value, context) {
                                                return value.toFixed(2) + '%';
                                            }
                                        }
                                    }
                                },
                            });

                        }
                                            
                    }
                }
            )
        }
    });
    selInitialPeriod.addEventListener('input', function(){
        if(sel_report.options[sel_report.selectedIndex].value != '' && selInitialPeriod.value != '' && selFinalPeriod.value){
            console.log('ok')
        }
    });
    selFinalPeriod.addEventListener('input', function(){
        if(sel_report.options[sel_report.selectedIndex].value != '' && selInitialPeriod.value != '' && selFinalPeriod.value){
            console.log('ok')
        }
    });

</script>
