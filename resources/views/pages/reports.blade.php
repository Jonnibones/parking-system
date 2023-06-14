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

                <form target="_blank" method="post">
                    <input type="hidden" value="table" name="table">
                    <div class="d-flex justify-content-end">
                      <button name="btnsTable" id="btnPdf" style="display:none;" data-size="s"
                      class="ladda-button" data-color="purple" data-style="zoom-in" type="submit">
                        <i class="ion-printer"></i> PDF
                      </button>
                      <button name="btnsTable" id="btnExcel" style="display:none;" class="btn btn-info ml-2" formaction="#" type="submit">
                        <i class="ion-stats-bars"></i> Excel
                      </button>
                    </div>
                  </form>
                  
                

                <div style="margin-top: 20px;" id="divCharts" class="container">
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
    //Métodos para realizar a busca de acordo com os dados dos inputs
    const sel_report = document.querySelector("#sel_report");
    const selInitialPeriod = document.querySelector("#selInitialPeriod");
    const selFinalPeriod = document.querySelector("#selFinalPeriod");
    sel_report.addEventListener('change', function(){

        let reportTitle = document.querySelector("#reportTitle");
        let reportDiv = document.querySelector("#reportDiv");
        reportDiv.innerHTML = '';
        reportTitle.innerHTML = '';

        document.getElementById("divCharts").style.display = 'none';
        let h4s = document.querySelectorAll("h4");
        h4s.forEach(function(h4){
            h4.innerHTML = '';
        });

        var chartContainer1 = document.getElementById('chartContainer1');
        var chartContainer2 = document.getElementById('chartContainer2');
        var chartContainer3 = document.getElementById('chartContainer3');

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
                    console.log(data)

                    if(type == 'Services'){
                        if(data.length > 0){

                            document.getElementById("divCharts").style.display = 'block';

                            if(data[0].type == 'service'){

                                reportTitle.hidden = false;
                                reportTitle.innerHTML = 'Serviços';
                                
                                var tableContent = '<table id="table" class="table table-striped">' +
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

                                var btnsTable = document.querySelectorAll("button[name='btnsTable']");
                                btnsTable.forEach(function(btn){
                                    btn.style.display = 'block';
                                });


                                //Gráfico 1
                                var heading = document.createElement('h4');
                                heading.textContent = '';
                                heading.textContent = 'Tipo serviço';
                                chartContainer1.parentNode.insertBefore(heading, chartContainer1);
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
                                chartContainer2.parentNode.insertBefore(heading, chartContainer2);
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
                                chartContainer3.parentNode.insertBefore(heading, chartContainer3);
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
                                                
                            }else{
                            var tableContent = '<table id="table" class="table table-striped">' +
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

                                
                            tableContent += '<tr>' +
                                    '<td>Nenhum registro encontrado</td>' +
                                    '<td>Nenhum registro encontrado</td>' +
                                    '<td>Nenhum registro encontrado</td>' +
                                    '<td>Nenhum registro encontrado</td>' +
                                    '<td>Nenhum registro encontrado</td>'+
                                    '<td>Nenhum registro encontrado</td>' +
                                    '<td>Nenhum registro encontrado</td>' +
                                '</tr>';
                                

                            tableContent += '</tbody>' +
                                '</table>';

                            reportDiv.innerHTML = tableContent;
                            reportDiv.hidden = false;
                            document.getElementById("divCharts").style.display = 'none';
                            let h4s = document.querySelectorAll("h4");
                            h4s.forEach(function(h4){
                                h4.innerHTML = '';
                            });

                        }
                    }else if(type == 'Customers'){
                        if(data.length > 0){

                            document.getElementById("divCharts").style.display = 'block';

                            if(data[0].type == 'customer'){

                                reportTitle.hidden = false;
                                reportTitle.innerHTML = 'Clientes';
                                
                                var tableContent = '<table id="table" class="table table-striped">' +
                                                        '<thead>' +
                                                            '<tr>' +
                                                            '<th>Id cliente</th>' +
                                                            '<th>Nome</th>' +
                                                            '<th>N° habilitação</th>' +
                                                            '<th>E-mail</th>' +
                                                            '<th>Telefone</th>' +
                                                            '<th>Endereço</th>' +
                                                            '<th>Gênero</th>' +
                                                            '<th>Idade</th>' +
                                                            '<th>Data cadastro</th>' +
                                                            '</tr>' +
                                                        '</thead>' +
                                                        '<tbody>';

                                data.forEach(function (customer) {
                                    tableContent += '<tr>' +
                                        '<td>' + customer.id + '</td>' +
                                        '<td>' + customer.name + '</td>' +
                                        '<td>' + customer.driving_license_number + '</td>' +
                                        '<td>' + customer.email + '</td>' +
                                        '<td>' + customer.phone + '</td>' +
                                        '<td>' + customer.address + '</td>' +
                                        '<td>' + (customer.gender === 'M' ? 'Masculino' : 'Feminino') + '</td>' +
                                        '<td>' + customer.age + '</td>' +
                                        '<td>' + new Date(customer.created_at).toLocaleString('pt-BR', { day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit', second: '2-digit' }) + '</td>' +
                                        '</tr>';
                                });

                                tableContent += '</tbody>' +
                                    '</table>';

                                reportDiv.innerHTML = tableContent;
                                reportDiv.hidden = false;

                                var btnsTable = document.querySelectorAll("button[name='btnsTable']");
                                btnsTable.forEach(function(btn){
                                    btn.style.display = 'block';
                                });


                                //Gráfico 1
                                var heading = document.createElement('h4');
                                heading.textContent = '';
                                heading.textContent = 'Gênero';
                                chartContainer1.parentNode.insertBefore(heading, chartContainer1);
                                var ctx = document.getElementById('chartContainer1').getContext('2d');
                                var customerTypes = [];
                                var customerCounts = [];
                                var backgroundColors = [];
                                data.forEach(function (customer) {
                                    var typeIndex = customerTypes.indexOf(customer.gender);

                                    if (typeIndex === -1) {
                                        customerTypes.push(customer.gender);
                                        customerCounts.push(1);
                                    } else {
                                        customerCounts[typeIndex]++;
                                    }
                                });
                                var totalCustomer = customerCounts.reduce(function (a, b) {
                                    return a + b;
                                }, 0);
                                var percentageData = customerCounts.map(function (count) {
                                    return (count / totalCustomer) * 100;
                                });
                                for (let i = 0; i < customerTypes.length; i++) {
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
                                        labels: customerTypes,
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
                                heading.textContent = '';
                                heading.textContent = 'Idade';
                                chartContainer2.parentNode.insertBefore(heading, chartContainer2);
                                var ctx = document.getElementById('chartContainer2').getContext('2d');
                                var customerTypes = [];
                                var customerCounts = [];
                                var backgroundColors = [];
                                data.forEach(function (customer) {
                                    var typeIndex = customerTypes.indexOf(customer.age);

                                    if (typeIndex === -1) {
                                        customerTypes.push(customer.age);
                                        customerCounts.push(1);
                                    } else {
                                        customerCounts[typeIndex]++;
                                    }
                                });
                                var totalCustomer = customerCounts.reduce(function (a, b) {
                                    return a + b;
                                }, 0);
                                var percentageData = customerCounts.map(function (count) {
                                    return (count / totalCustomer) * 100;
                                });
                                for (let i = 0; i < customerTypes.length; i++) {
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
                                        labels: customerTypes,
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
                                heading.textContent = '';
                                heading.textContent = 'Mês de cadastro';
                                chartContainer3.parentNode.insertBefore(heading, chartContainer3);
                                var ctx = document.getElementById('chartContainer3').getContext('2d');
                                var customerTypes = [];
                                var customerCounts = [];
                                var backgroundColors = [];
                                data.forEach(function (customer) {
                                    var typeIndex = customerTypes.indexOf(customer.age);

                                    if (typeIndex === -1) {
                                        customerTypes.push(customer.age);
                                        customerCounts.push(1);
                                    } else {
                                        customerCounts[typeIndex]++;
                                    }
                                });

                                // Extrair o mês da data de criação de cada cliente
                                var months = data.map(function(customer) {
                                return new Date(customer.created_at).getMonth() + 1; // +1 porque os meses em JavaScript começam em 0
                                });

                                // Calcular a contagem de cada mês
                                var monthCounts = [];
                                for (var i = 1; i <= 12; i++) {
                                var count = months.filter(function(month) {
                                    return month === i;
                                }).length;
                                monthCounts.push(count);
                                }

                                // Calcular o total de clientes
                                var totalCustomers = monthCounts.reduce(function(a, b) {
                                return a + b;
                                }, 0);

                                // Calcular as porcentagens dos meses
                                var monthPercentages = monthCounts.map(function(count) {
                                return (count / totalCustomers) * 100;
                                });

                                // Gerar cores aleatórias para os meses
                                var backgroundColors = [];
                                for (var i = 0; i < 12; i++) {
                                backgroundColors.push(getRandomColor());
                                }

                                // Criar o gráfico de pizza
                                var ctx = document.getElementById('chartContainer3').getContext('2d');
                                var myChart = new Chart(ctx, {
                                type: 'pie',
                                data: {
                                    labels: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
                                    datasets: [{
                                    data: monthPercentages,
                                    backgroundColor: backgroundColors,
                                    }],
                                },
                                options: {
                                    responsive: true,
                                    plugins: {
                                    datalabels: {
                                        formatter: function(value, context) {
                                        return value.toFixed(2) + '%';
                                        }
                                    }
                                    }
                                },
                                });

                                

                            }               
                            }else{
                                var tableContent = '<table id="table" class="table table-striped">' +
                                                        '<thead>' +
                                                            '<tr>' +
                                                            '<th>Id cliente</th>' +
                                                            '<th>Nome</th>' +
                                                            '<th>N° habilitação</th>' +
                                                            '<th>E-mail</th>' +
                                                            '<th>Telefone</th>' +
                                                            '<th>Endereço</th>' +
                                                            '<th>Gênero</th>' +
                                                            '<th>Idade</th>' +
                                                            '<th>Data cadastro</th>' +
                                                            '</tr>' +
                                                        '</thead>' +
                                                        '<tbody>';

                                
                            tableContent += '<tr>' +
                                    '<td>Nenhum registro encontrado</td>' +
                                    '<td>Nenhum registro encontrado</td>' +
                                    '<td>Nenhum registro encontrado</td>' +
                                    '<td>Nenhum registro encontrado</td>' +
                                    '<td>Nenhum registro encontrado</td>'+
                                    '<td>Nenhum registro encontrado</td>' +
                                    '<td>Nenhum registro encontrado</td>' +
                                    '<td>Nenhum registro encontrado</td>' +
                                    '<td>Nenhum registro encontrado</td>' +
                                '</tr>';
                                

                            tableContent += '</tbody>' +
                                '</table>';

                            reportDiv.innerHTML = tableContent;
                            reportDiv.hidden = false;
                            document.getElementById("divCharts").style.display = 'none';
                            let h4s = document.querySelectorAll("h4");
                            h4s.forEach(function(h4){
                                h4.innerHTML = '';
                            });

                        }
                    }else{

                        if(data.length > 0){

                            document.getElementById("divCharts").style.display = 'block';

                            if(data[0].type == 'reservation'){

                                reportTitle.hidden = false;
                                reportTitle.innerHTML = 'Reservas';
                                
                                var tableContent = '<table id="table" class="table table-striped">' +
                                                        '<thead>' +
                                                            '<tr>' +
                                                            '<th>Id reserva</th>' +
                                                            '<th>Nome cliente</th>' +
                                                            '<th>N° vaga</th>' +
                                                            '<th>Data reserva</th>' +
                                                            '</tr>' +
                                                        '</thead>' +
                                                        '<tbody>';

                                data.forEach(function (reservation) {
                                    tableContent += '<tr>' +
                                        '<td>' + reservation.id + '</td>' +
                                        '<td>' + reservation.customer + '</td>' +
                                        '<td>' + reservation.parking_space_number + '</td>' +
                                        '<td>' + new Date(reservation.created_at).toLocaleString('pt-BR', { day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit', second: '2-digit' }) + '</td>' +
                                        '</tr>';
                                });

                                tableContent += '</tbody>' +
                                    '</table>';

                                reportDiv.innerHTML = tableContent;
                                reportDiv.hidden = false;

                                var btnsTable = document.querySelectorAll("button[name='btnsTable']");
                                btnsTable.forEach(function(btn){
                                    btn.style.display = 'block';
                                });


                                //Gráfico 1
                                var heading = document.createElement('h4');
                                heading.textContent = '';
                                heading.textContent = 'Cliente';
                                chartContainer1.parentNode.insertBefore(heading, chartContainer1);
                                var ctx = document.getElementById('chartContainer1').getContext('2d');
                                var reservationTypes = [];
                                var reservationCounts = [];
                                var backgroundColors = [];
                                data.forEach(function (reservation) {
                                    var typeIndex = reservationTypes.indexOf(reservation.customer);

                                    if (typeIndex === -1) {
                                        reservationTypes.push(reservation.customer);
                                        reservationCounts.push(1);
                                    } else {
                                        reservationCounts[typeIndex]++;
                                    }
                                });
                                var totalReservation = reservationCounts.reduce(function (a, b) {
                                    return a + b;
                                }, 0);
                                var percentageData = reservationCounts.map(function (count) {
                                    return (count / totalReservation) * 100;
                                });
                                for (let i = 0; i < reservationTypes.length; i++) {
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
                                        labels: reservationTypes,
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
                                heading.textContent = '';
                                heading.textContent = 'N° vaga';
                                chartContainer2.parentNode.insertBefore(heading, chartContainer2);
                                var ctx = document.getElementById('chartContainer2').getContext('2d');
                                var reservationTypes = [];
                                var reservationCounts = [];
                                var backgroundColors = [];
                                data.forEach(function (reservation) {
                                    var typeIndex = reservationTypes.indexOf(reservation.parking_space_number);

                                    if (typeIndex === -1) {
                                        reservationTypes.push(reservation.parking_space_number);
                                        reservationCounts.push(1);
                                    } else {
                                        reservationCounts[typeIndex]++;
                                    }
                                });
                                var totalReservation = reservationCounts.reduce(function (a, b) {
                                    return a + b;
                                }, 0);
                                var percentageData = reservationCounts.map(function (count) {
                                    return (count / totalReservation) * 100;
                                });
                                for (let i = 0; i < reservationTypes.length; i++) {
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
                                        labels: reservationTypes,
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
                                heading.textContent = '';
                                heading.textContent = 'Mês de reserva';
                                chartContainer3.parentNode.insertBefore(heading, chartContainer3);
                                var ctx = document.getElementById('chartContainer3').getContext('2d');
                                var reservationTypes = [];
                                var reservationCounts = [];
                                var backgroundColors = [];
                                data.forEach(function (reservation) {
                                    var typeIndex = reservationTypes.indexOf(reservation.age);

                                    if (typeIndex === -1) {
                                        reservationTypes.push(reservation.age);
                                        reservationCounts.push(1);
                                    } else {
                                        reservationCounts[typeIndex]++;
                                    }
                                });

                                // Extrair o mês da data de criação de cada cliente
                                var months = data.map(function(reservation) {
                                return new Date(reservation.created_at).getMonth() + 1; // +1 porque os meses em JavaScript começam em 0
                                });

                                // Calcular a contagem de cada mês
                                var monthCounts = [];
                                for (var i = 1; i <= 12; i++) {
                                var count = months.filter(function(month) {
                                    return month === i;
                                }).length;
                                monthCounts.push(count);
                                }

                                // Calcular o total de clientes
                                var totalReservation = monthCounts.reduce(function(a, b) {
                                return a + b;
                                }, 0);

                                // Calcular as porcentagens dos meses
                                var monthPercentages = monthCounts.map(function(count) {
                                return (count / totalReservation) * 100;
                                });

                                // Gerar cores aleatórias para os meses
                                var backgroundColors = [];
                                for (var i = 0; i < 12; i++) {
                                backgroundColors.push(getRandomColor());
                                }

                                // Criar o gráfico de pizza
                                var ctx = document.getElementById('chartContainer3').getContext('2d');
                                var myChart = new Chart(ctx, {
                                type: 'pie',
                                data: {
                                    labels: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
                                    datasets: [{
                                    data: monthPercentages,
                                    backgroundColor: backgroundColors,
                                    }],
                                },
                                options: {
                                    responsive: true,
                                    plugins: {
                                    datalabels: {
                                        formatter: function(value, context) {
                                        return value.toFixed(2) + '%';
                                        }
                                    }
                                    }
                                },
                                });

                                

                            }               
                            }else{
                                var tableContent = '<table id="table" class="table table-striped">' +
                                                        '<thead>' +
                                                            '<tr>' +
                                                            '<th>Id reserva</th>' +
                                                            '<th>Nome cliente</th>' +
                                                            '<th>N° vaga</th>' +
                                                            '<th>Data reserva</th>' +
                                                            '</tr>' +
                                                        '</thead>' +
                                                        '<tbody>';

                                
                            tableContent += '<tr>' +
                                    '<td>Nenhum registro encontrado</td>' +
                                    '<td>Nenhum registro encontrado</td>' +
                                    '<td>Nenhum registro encontrado</td>' +
                                    '<td>Nenhum registro encontrado</td>' +
                                '</tr>';
                                

                            tableContent += '</tbody>' +
                                '</table>';

                            reportDiv.innerHTML = tableContent;
                            reportDiv.hidden = false;
                            document.getElementById("divCharts").style.display = 'none';
                            let h4s = document.querySelectorAll("h4");
                            h4s.forEach(function(h4){
                                h4.innerHTML = '';
                            });

                            }  
                    }
                    
                }
            )
        }
    });
    selInitialPeriod.addEventListener('input', function(){
        
        let reportTitle = document.querySelector("#reportTitle");
        let reportDiv = document.querySelector("#reportDiv");
        reportDiv.innerHTML = '';
        reportTitle.innerHTML = '';

        document.getElementById("divCharts").style.display = 'none';
        let h4s = document.querySelectorAll("h4");
        h4s.forEach(function(h4){
            h4.innerHTML = '';
        });

        var chartContainer1 = document.getElementById('chartContainer1');
        var chartContainer2 = document.getElementById('chartContainer2');
        var chartContainer3 = document.getElementById('chartContainer3');

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
                    console.log(data)

                    if(type == 'Services'){
                        if(data.length > 0){

                            document.getElementById("divCharts").style.display = 'block';

                            if(data[0].type == 'service'){

                                reportTitle.hidden = false;
                                reportTitle.innerHTML = 'Serviços';
                                
                                var tableContent = '<table id="table" class="table table-striped">' +
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

                                var btnsTable = document.querySelectorAll("button[name='btnsTable']");
                                btnsTable.forEach(function(btn){
                                    btn.style.display = 'block';
                                });


                                //Gráfico 1
                                var heading = document.createElement('h4');
                                heading.textContent = '';
                                heading.textContent = 'Tipo serviço';
                                chartContainer1.parentNode.insertBefore(heading, chartContainer1);
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
                                chartContainer2.parentNode.insertBefore(heading, chartContainer2);
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
                                chartContainer3.parentNode.insertBefore(heading, chartContainer3);
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
                                                
                            }else{
                            var tableContent = '<table id="table" class="table table-striped">' +
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

                                
                            tableContent += '<tr>' +
                                    '<td>Nenhum registro encontrado</td>' +
                                    '<td>Nenhum registro encontrado</td>' +
                                    '<td>Nenhum registro encontrado</td>' +
                                    '<td>Nenhum registro encontrado</td>' +
                                    '<td>Nenhum registro encontrado</td>'+
                                    '<td>Nenhum registro encontrado</td>' +
                                    '<td>Nenhum registro encontrado</td>' +
                                '</tr>';
                                

                            tableContent += '</tbody>' +
                                '</table>';

                            reportDiv.innerHTML = tableContent;
                            reportDiv.hidden = false;
                            document.getElementById("divCharts").style.display = 'none';
                            let h4s = document.querySelectorAll("h4");
                            h4s.forEach(function(h4){
                                h4.innerHTML = '';
                            });

                        }
                    }else if(type == 'Customers'){
                        if(data.length > 0){

                            document.getElementById("divCharts").style.display = 'block';

                            if(data[0].type == 'customer'){

                                reportTitle.hidden = false;
                                reportTitle.innerHTML = 'Clientes';
                                
                                var tableContent = '<table id="table" class="table table-striped">' +
                                                        '<thead>' +
                                                            '<tr>' +
                                                            '<th>Id cliente</th>' +
                                                            '<th>Nome</th>' +
                                                            '<th>N° habilitação</th>' +
                                                            '<th>E-mail</th>' +
                                                            '<th>Telefone</th>' +
                                                            '<th>Endereço</th>' +
                                                            '<th>Gênero</th>' +
                                                            '<th>Idade</th>' +
                                                            '<th>Data cadastro</th>' +
                                                            '</tr>' +
                                                        '</thead>' +
                                                        '<tbody>';

                                data.forEach(function (customer) {
                                    tableContent += '<tr>' +
                                        '<td>' + customer.id + '</td>' +
                                        '<td>' + customer.name + '</td>' +
                                        '<td>' + customer.driving_license_number + '</td>' +
                                        '<td>' + customer.email + '</td>' +
                                        '<td>' + customer.phone + '</td>' +
                                        '<td>' + customer.address + '</td>' +
                                        '<td>' + (customer.gender === 'M' ? 'Masculino' : 'Feminino') + '</td>' +
                                        '<td>' + customer.age + '</td>' +
                                        '<td>' + new Date(customer.created_at).toLocaleString('pt-BR', { day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit', second: '2-digit' }) + '</td>' +
                                        '</tr>';
                                });

                                tableContent += '</tbody>' +
                                    '</table>';

                                reportDiv.innerHTML = tableContent;
                                reportDiv.hidden = false;

                                var btnsTable = document.querySelectorAll("button[name='btnsTable']");
                                btnsTable.forEach(function(btn){
                                    btn.style.display = 'block';
                                });


                                //Gráfico 1
                                var heading = document.createElement('h4');
                                heading.textContent = '';
                                heading.textContent = 'Gênero';
                                chartContainer1.parentNode.insertBefore(heading, chartContainer1);
                                var ctx = document.getElementById('chartContainer1').getContext('2d');
                                var customerTypes = [];
                                var customerCounts = [];
                                var backgroundColors = [];
                                data.forEach(function (customer) {
                                    var typeIndex = customerTypes.indexOf(customer.gender);

                                    if (typeIndex === -1) {
                                        customerTypes.push(customer.gender);
                                        customerCounts.push(1);
                                    } else {
                                        customerCounts[typeIndex]++;
                                    }
                                });
                                var totalCustomer = customerCounts.reduce(function (a, b) {
                                    return a + b;
                                }, 0);
                                var percentageData = customerCounts.map(function (count) {
                                    return (count / totalCustomer) * 100;
                                });
                                for (let i = 0; i < customerTypes.length; i++) {
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
                                        labels: customerTypes,
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
                                heading.textContent = '';
                                heading.textContent = 'Idade';
                                chartContainer2.parentNode.insertBefore(heading, chartContainer2);
                                var ctx = document.getElementById('chartContainer2').getContext('2d');
                                var customerTypes = [];
                                var customerCounts = [];
                                var backgroundColors = [];
                                data.forEach(function (customer) {
                                    var typeIndex = customerTypes.indexOf(customer.age);

                                    if (typeIndex === -1) {
                                        customerTypes.push(customer.age);
                                        customerCounts.push(1);
                                    } else {
                                        customerCounts[typeIndex]++;
                                    }
                                });
                                var totalCustomer = customerCounts.reduce(function (a, b) {
                                    return a + b;
                                }, 0);
                                var percentageData = customerCounts.map(function (count) {
                                    return (count / totalCustomer) * 100;
                                });
                                for (let i = 0; i < customerTypes.length; i++) {
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
                                        labels: customerTypes,
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
                                heading.textContent = '';
                                heading.textContent = 'Mês de cadastro';
                                chartContainer3.parentNode.insertBefore(heading, chartContainer3);
                                var ctx = document.getElementById('chartContainer3').getContext('2d');
                                var customerTypes = [];
                                var customerCounts = [];
                                var backgroundColors = [];
                                data.forEach(function (customer) {
                                    var typeIndex = customerTypes.indexOf(customer.age);

                                    if (typeIndex === -1) {
                                        customerTypes.push(customer.age);
                                        customerCounts.push(1);
                                    } else {
                                        customerCounts[typeIndex]++;
                                    }
                                });

                                // Extrair o mês da data de criação de cada cliente
                                var months = data.map(function(customer) {
                                return new Date(customer.created_at).getMonth() + 1; // +1 porque os meses em JavaScript começam em 0
                                });

                                // Calcular a contagem de cada mês
                                var monthCounts = [];
                                for (var i = 1; i <= 12; i++) {
                                var count = months.filter(function(month) {
                                    return month === i;
                                }).length;
                                monthCounts.push(count);
                                }

                                // Calcular o total de clientes
                                var totalCustomers = monthCounts.reduce(function(a, b) {
                                return a + b;
                                }, 0);

                                // Calcular as porcentagens dos meses
                                var monthPercentages = monthCounts.map(function(count) {
                                return (count / totalCustomers) * 100;
                                });

                                // Gerar cores aleatórias para os meses
                                var backgroundColors = [];
                                for (var i = 0; i < 12; i++) {
                                backgroundColors.push(getRandomColor());
                                }

                                // Criar o gráfico de pizza
                                var ctx = document.getElementById('chartContainer3').getContext('2d');
                                var myChart = new Chart(ctx, {
                                type: 'pie',
                                data: {
                                    labels: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
                                    datasets: [{
                                    data: monthPercentages,
                                    backgroundColor: backgroundColors,
                                    }],
                                },
                                options: {
                                    responsive: true,
                                    plugins: {
                                    datalabels: {
                                        formatter: function(value, context) {
                                        return value.toFixed(2) + '%';
                                        }
                                    }
                                    }
                                },
                                });

                                

                            }               
                            }else{
                                var tableContent = '<table id="table" class="table table-striped">' +
                                                        '<thead>' +
                                                            '<tr>' +
                                                            '<th>Id cliente</th>' +
                                                            '<th>Nome</th>' +
                                                            '<th>N° habilitação</th>' +
                                                            '<th>E-mail</th>' +
                                                            '<th>Telefone</th>' +
                                                            '<th>Endereço</th>' +
                                                            '<th>Gênero</th>' +
                                                            '<th>Idade</th>' +
                                                            '<th>Data cadastro</th>' +
                                                            '</tr>' +
                                                        '</thead>' +
                                                        '<tbody>';

                                
                            tableContent += '<tr>' +
                                    '<td>Nenhum registro encontrado</td>' +
                                    '<td>Nenhum registro encontrado</td>' +
                                    '<td>Nenhum registro encontrado</td>' +
                                    '<td>Nenhum registro encontrado</td>' +
                                    '<td>Nenhum registro encontrado</td>'+
                                    '<td>Nenhum registro encontrado</td>' +
                                    '<td>Nenhum registro encontrado</td>' +
                                    '<td>Nenhum registro encontrado</td>' +
                                    '<td>Nenhum registro encontrado</td>' +
                                '</tr>';
                                

                            tableContent += '</tbody>' +
                                '</table>';

                            reportDiv.innerHTML = tableContent;
                            reportDiv.hidden = false;
                            document.getElementById("divCharts").style.display = 'none';
                            let h4s = document.querySelectorAll("h4");
                            h4s.forEach(function(h4){
                                h4.innerHTML = '';
                            });

                        }
                    }else{

                        if(data.length > 0){

                            document.getElementById("divCharts").style.display = 'block';

                            if(data[0].type == 'reservation'){

                                reportTitle.hidden = false;
                                reportTitle.innerHTML = 'Reservas';
                                
                                var tableContent = '<table id="table" class="table table-striped">' +
                                                        '<thead>' +
                                                            '<tr>' +
                                                            '<th>Id reserva</th>' +
                                                            '<th>Nome cliente</th>' +
                                                            '<th>N° vaga</th>' +
                                                            '<th>Data reserva</th>' +
                                                            '</tr>' +
                                                        '</thead>' +
                                                        '<tbody>';

                                data.forEach(function (reservation) {
                                    tableContent += '<tr>' +
                                        '<td>' + reservation.id + '</td>' +
                                        '<td>' + reservation.customer + '</td>' +
                                        '<td>' + reservation.parking_space_number + '</td>' +
                                        '<td>' + new Date(reservation.created_at).toLocaleString('pt-BR', { day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit', second: '2-digit' }) + '</td>' +
                                        '</tr>';
                                });

                                tableContent += '</tbody>' +
                                    '</table>';

                                reportDiv.innerHTML = tableContent;
                                reportDiv.hidden = false;

                                var btnsTable = document.querySelectorAll("button[name='btnsTable']");
                                btnsTable.forEach(function(btn){
                                    btn.style.display = 'block';
                                });


                                //Gráfico 1
                                var heading = document.createElement('h4');
                                heading.textContent = '';
                                heading.textContent = 'Cliente';
                                chartContainer1.parentNode.insertBefore(heading, chartContainer1);
                                var ctx = document.getElementById('chartContainer1').getContext('2d');
                                var reservationTypes = [];
                                var reservationCounts = [];
                                var backgroundColors = [];
                                data.forEach(function (reservation) {
                                    var typeIndex = reservationTypes.indexOf(reservation.customer);

                                    if (typeIndex === -1) {
                                        reservationTypes.push(reservation.customer);
                                        reservationCounts.push(1);
                                    } else {
                                        reservationCounts[typeIndex]++;
                                    }
                                });
                                var totalReservation = reservationCounts.reduce(function (a, b) {
                                    return a + b;
                                }, 0);
                                var percentageData = reservationCounts.map(function (count) {
                                    return (count / totalReservation) * 100;
                                });
                                for (let i = 0; i < reservationTypes.length; i++) {
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
                                        labels: reservationTypes,
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
                                heading.textContent = '';
                                heading.textContent = 'N° vaga';
                                chartContainer2.parentNode.insertBefore(heading, chartContainer2);
                                var ctx = document.getElementById('chartContainer2').getContext('2d');
                                var reservationTypes = [];
                                var reservationCounts = [];
                                var backgroundColors = [];
                                data.forEach(function (reservation) {
                                    var typeIndex = reservationTypes.indexOf(reservation.parking_space_number);

                                    if (typeIndex === -1) {
                                        reservationTypes.push(reservation.parking_space_number);
                                        reservationCounts.push(1);
                                    } else {
                                        reservationCounts[typeIndex]++;
                                    }
                                });
                                var totalReservation = reservationCounts.reduce(function (a, b) {
                                    return a + b;
                                }, 0);
                                var percentageData = reservationCounts.map(function (count) {
                                    return (count / totalReservation) * 100;
                                });
                                for (let i = 0; i < reservationTypes.length; i++) {
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
                                        labels: reservationTypes,
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
                                heading.textContent = '';
                                heading.textContent = 'Mês de reserva';
                                chartContainer3.parentNode.insertBefore(heading, chartContainer3);
                                var ctx = document.getElementById('chartContainer3').getContext('2d');
                                var reservationTypes = [];
                                var reservationCounts = [];
                                var backgroundColors = [];
                                data.forEach(function (reservation) {
                                    var typeIndex = reservationTypes.indexOf(reservation.age);

                                    if (typeIndex === -1) {
                                        reservationTypes.push(reservation.age);
                                        reservationCounts.push(1);
                                    } else {
                                        reservationCounts[typeIndex]++;
                                    }
                                });

                                // Extrair o mês da data de criação de cada cliente
                                var months = data.map(function(reservation) {
                                return new Date(reservation.created_at).getMonth() + 1; // +1 porque os meses em JavaScript começam em 0
                                });

                                // Calcular a contagem de cada mês
                                var monthCounts = [];
                                for (var i = 1; i <= 12; i++) {
                                var count = months.filter(function(month) {
                                    return month === i;
                                }).length;
                                monthCounts.push(count);
                                }

                                // Calcular o total de clientes
                                var totalReservation = monthCounts.reduce(function(a, b) {
                                return a + b;
                                }, 0);

                                // Calcular as porcentagens dos meses
                                var monthPercentages = monthCounts.map(function(count) {
                                return (count / totalReservation) * 100;
                                });

                                // Gerar cores aleatórias para os meses
                                var backgroundColors = [];
                                for (var i = 0; i < 12; i++) {
                                backgroundColors.push(getRandomColor());
                                }

                                // Criar o gráfico de pizza
                                var ctx = document.getElementById('chartContainer3').getContext('2d');
                                var myChart = new Chart(ctx, {
                                type: 'pie',
                                data: {
                                    labels: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
                                    datasets: [{
                                    data: monthPercentages,
                                    backgroundColor: backgroundColors,
                                    }],
                                },
                                options: {
                                    responsive: true,
                                    plugins: {
                                    datalabels: {
                                        formatter: function(value, context) {
                                        return value.toFixed(2) + '%';
                                        }
                                    }
                                    }
                                },
                                });

                                

                            }               
                            }else{
                                var tableContent = '<table id="table" class="table table-striped">' +
                                                        '<thead>' +
                                                            '<tr>' +
                                                            '<th>Id reserva</th>' +
                                                            '<th>Nome cliente</th>' +
                                                            '<th>N° vaga</th>' +
                                                            '<th>Data reserva</th>' +
                                                            '</tr>' +
                                                        '</thead>' +
                                                        '<tbody>';

                                
                            tableContent += '<tr>' +
                                    '<td>Nenhum registro encontrado</td>' +
                                    '<td>Nenhum registro encontrado</td>' +
                                    '<td>Nenhum registro encontrado</td>' +
                                    '<td>Nenhum registro encontrado</td>' +
                                '</tr>';
                                

                            tableContent += '</tbody>' +
                                '</table>';

                            reportDiv.innerHTML = tableContent;
                            reportDiv.hidden = false;
                            document.getElementById("divCharts").style.display = 'none';
                            let h4s = document.querySelectorAll("h4");
                            h4s.forEach(function(h4){
                                h4.innerHTML = '';
                            });

                            }  
                    }
                    
                }
            )
        }
        
    });
    selFinalPeriod.addEventListener('input', function(){
        let reportTitle = document.querySelector("#reportTitle");
        let reportDiv = document.querySelector("#reportDiv");
        reportDiv.innerHTML = '';
        reportTitle.innerHTML = '';

        document.getElementById("divCharts").style.display = 'none';
        let h4s = document.querySelectorAll("h4");
        h4s.forEach(function(h4){
            h4.innerHTML = '';
        });

        var chartContainer1 = document.getElementById('chartContainer1');
        var chartContainer2 = document.getElementById('chartContainer2');
        var chartContainer3 = document.getElementById('chartContainer3');

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
                    console.log(data)

                    if(type == 'Services'){
                        if(data.length > 0){

                            document.getElementById("divCharts").style.display = 'block';

                            if(data[0].type == 'service'){

                                reportTitle.hidden = false;
                                reportTitle.innerHTML = 'Serviços';
                                
                                var tableContent = '<table id="table" class="table table-striped">' +
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

                                var btnsTable = document.querySelectorAll("button[name='btnsTable']");
                                btnsTable.forEach(function(btn){
                                    btn.style.display = 'block';
                                });


                                //Gráfico 1
                                var heading = document.createElement('h4');
                                heading.textContent = '';
                                heading.textContent = 'Tipo serviço';
                                chartContainer1.parentNode.insertBefore(heading, chartContainer1);
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
                                chartContainer2.parentNode.insertBefore(heading, chartContainer2);
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
                                chartContainer3.parentNode.insertBefore(heading, chartContainer3);
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
                                                
                            }else{
                            var tableContent = '<table id="table" class="table table-striped">' +
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

                                
                            tableContent += '<tr>' +
                                    '<td>Nenhum registro encontrado</td>' +
                                    '<td>Nenhum registro encontrado</td>' +
                                    '<td>Nenhum registro encontrado</td>' +
                                    '<td>Nenhum registro encontrado</td>' +
                                    '<td>Nenhum registro encontrado</td>'+
                                    '<td>Nenhum registro encontrado</td>' +
                                    '<td>Nenhum registro encontrado</td>' +
                                '</tr>';
                                

                            tableContent += '</tbody>' +
                                '</table>';

                            reportDiv.innerHTML = tableContent;
                            reportDiv.hidden = false;
                            document.getElementById("divCharts").style.display = 'none';
                            let h4s = document.querySelectorAll("h4");
                            h4s.forEach(function(h4){
                                h4.innerHTML = '';
                            });

                        }
                    }else if(type == 'Customers'){
                        if(data.length > 0){

                            document.getElementById("divCharts").style.display = 'block';

                            if(data[0].type == 'customer'){

                                reportTitle.hidden = false;
                                reportTitle.innerHTML = 'Clientes';
                                
                                var tableContent = '<table id="table" class="table table-striped">' +
                                                        '<thead>' +
                                                            '<tr>' +
                                                            '<th>Id cliente</th>' +
                                                            '<th>Nome</th>' +
                                                            '<th>N° habilitação</th>' +
                                                            '<th>E-mail</th>' +
                                                            '<th>Telefone</th>' +
                                                            '<th>Endereço</th>' +
                                                            '<th>Gênero</th>' +
                                                            '<th>Idade</th>' +
                                                            '<th>Data cadastro</th>' +
                                                            '</tr>' +
                                                        '</thead>' +
                                                        '<tbody>';

                                data.forEach(function (customer) {
                                    tableContent += '<tr>' +
                                        '<td>' + customer.id + '</td>' +
                                        '<td>' + customer.name + '</td>' +
                                        '<td>' + customer.driving_license_number + '</td>' +
                                        '<td>' + customer.email + '</td>' +
                                        '<td>' + customer.phone + '</td>' +
                                        '<td>' + customer.address + '</td>' +
                                        '<td>' + (customer.gender === 'M' ? 'Masculino' : 'Feminino') + '</td>' +
                                        '<td>' + customer.age + '</td>' +
                                        '<td>' + new Date(customer.created_at).toLocaleString('pt-BR', { day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit', second: '2-digit' }) + '</td>' +
                                        '</tr>';
                                });

                                tableContent += '</tbody>' +
                                    '</table>';

                                reportDiv.innerHTML = tableContent;
                                reportDiv.hidden = false;

                                var btnsTable = document.querySelectorAll("button[name='btnsTable']");
                                btnsTable.forEach(function(btn){
                                    btn.style.display = 'block';
                                });


                                //Gráfico 1
                                var heading = document.createElement('h4');
                                heading.textContent = '';
                                heading.textContent = 'Gênero';
                                chartContainer1.parentNode.insertBefore(heading, chartContainer1);
                                var ctx = document.getElementById('chartContainer1').getContext('2d');
                                var customerTypes = [];
                                var customerCounts = [];
                                var backgroundColors = [];
                                data.forEach(function (customer) {
                                    var typeIndex = customerTypes.indexOf(customer.gender);

                                    if (typeIndex === -1) {
                                        customerTypes.push(customer.gender);
                                        customerCounts.push(1);
                                    } else {
                                        customerCounts[typeIndex]++;
                                    }
                                });
                                var totalCustomer = customerCounts.reduce(function (a, b) {
                                    return a + b;
                                }, 0);
                                var percentageData = customerCounts.map(function (count) {
                                    return (count / totalCustomer) * 100;
                                });
                                for (let i = 0; i < customerTypes.length; i++) {
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
                                        labels: customerTypes,
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
                                heading.textContent = '';
                                heading.textContent = 'Idade';
                                chartContainer2.parentNode.insertBefore(heading, chartContainer2);
                                var ctx = document.getElementById('chartContainer2').getContext('2d');
                                var customerTypes = [];
                                var customerCounts = [];
                                var backgroundColors = [];
                                data.forEach(function (customer) {
                                    var typeIndex = customerTypes.indexOf(customer.age);

                                    if (typeIndex === -1) {
                                        customerTypes.push(customer.age);
                                        customerCounts.push(1);
                                    } else {
                                        customerCounts[typeIndex]++;
                                    }
                                });
                                var totalCustomer = customerCounts.reduce(function (a, b) {
                                    return a + b;
                                }, 0);
                                var percentageData = customerCounts.map(function (count) {
                                    return (count / totalCustomer) * 100;
                                });
                                for (let i = 0; i < customerTypes.length; i++) {
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
                                        labels: customerTypes,
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
                                heading.textContent = '';
                                heading.textContent = 'Mês de cadastro';
                                chartContainer3.parentNode.insertBefore(heading, chartContainer3);
                                var ctx = document.getElementById('chartContainer3').getContext('2d');
                                var customerTypes = [];
                                var customerCounts = [];
                                var backgroundColors = [];
                                data.forEach(function (customer) {
                                    var typeIndex = customerTypes.indexOf(customer.age);

                                    if (typeIndex === -1) {
                                        customerTypes.push(customer.age);
                                        customerCounts.push(1);
                                    } else {
                                        customerCounts[typeIndex]++;
                                    }
                                });

                                // Extrair o mês da data de criação de cada cliente
                                var months = data.map(function(customer) {
                                return new Date(customer.created_at).getMonth() + 1; // +1 porque os meses em JavaScript começam em 0
                                });

                                // Calcular a contagem de cada mês
                                var monthCounts = [];
                                for (var i = 1; i <= 12; i++) {
                                var count = months.filter(function(month) {
                                    return month === i;
                                }).length;
                                monthCounts.push(count);
                                }

                                // Calcular o total de clientes
                                var totalCustomers = monthCounts.reduce(function(a, b) {
                                return a + b;
                                }, 0);

                                // Calcular as porcentagens dos meses
                                var monthPercentages = monthCounts.map(function(count) {
                                return (count / totalCustomers) * 100;
                                });

                                // Gerar cores aleatórias para os meses
                                var backgroundColors = [];
                                for (var i = 0; i < 12; i++) {
                                backgroundColors.push(getRandomColor());
                                }

                                // Criar o gráfico de pizza
                                var ctx = document.getElementById('chartContainer3').getContext('2d');
                                var myChart = new Chart(ctx, {
                                type: 'pie',
                                data: {
                                    labels: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
                                    datasets: [{
                                    data: monthPercentages,
                                    backgroundColor: backgroundColors,
                                    }],
                                },
                                options: {
                                    responsive: true,
                                    plugins: {
                                    datalabels: {
                                        formatter: function(value, context) {
                                        return value.toFixed(2) + '%';
                                        }
                                    }
                                    }
                                },
                                });

                                

                            }               
                            }else{
                                var tableContent = '<table id="table" class="table table-striped">' +
                                                        '<thead>' +
                                                            '<tr>' +
                                                            '<th>Id cliente</th>' +
                                                            '<th>Nome</th>' +
                                                            '<th>N° habilitação</th>' +
                                                            '<th>E-mail</th>' +
                                                            '<th>Telefone</th>' +
                                                            '<th>Endereço</th>' +
                                                            '<th>Gênero</th>' +
                                                            '<th>Idade</th>' +
                                                            '<th>Data cadastro</th>' +
                                                            '</tr>' +
                                                        '</thead>' +
                                                        '<tbody>';

                                
                            tableContent += '<tr>' +
                                    '<td>Nenhum registro encontrado</td>' +
                                    '<td>Nenhum registro encontrado</td>' +
                                    '<td>Nenhum registro encontrado</td>' +
                                    '<td>Nenhum registro encontrado</td>' +
                                    '<td>Nenhum registro encontrado</td>'+
                                    '<td>Nenhum registro encontrado</td>' +
                                    '<td>Nenhum registro encontrado</td>' +
                                    '<td>Nenhum registro encontrado</td>' +
                                    '<td>Nenhum registro encontrado</td>' +
                                '</tr>';
                                

                            tableContent += '</tbody>' +
                                '</table>';

                            reportDiv.innerHTML = tableContent;
                            reportDiv.hidden = false;
                            document.getElementById("divCharts").style.display = 'none';
                            let h4s = document.querySelectorAll("h4");
                            h4s.forEach(function(h4){
                                h4.innerHTML = '';
                            });

                        }
                    }else{

                        if(data.length > 0){

                            document.getElementById("divCharts").style.display = 'block';

                            if(data[0].type == 'reservation'){

                                reportTitle.hidden = false;
                                reportTitle.innerHTML = 'Reservas';
                                
                                var tableContent = '<table id="table" class="table table-striped">' +
                                                        '<thead>' +
                                                            '<tr>' +
                                                            '<th>Id reserva</th>' +
                                                            '<th>Nome cliente</th>' +
                                                            '<th>N° vaga</th>' +
                                                            '<th>Data reserva</th>' +
                                                            '</tr>' +
                                                        '</thead>' +
                                                        '<tbody>';

                                data.forEach(function (reservation) {
                                    tableContent += '<tr>' +
                                        '<td>' + reservation.id + '</td>' +
                                        '<td>' + reservation.customer + '</td>' +
                                        '<td>' + reservation.parking_space_number + '</td>' +
                                        '<td>' + new Date(reservation.created_at).toLocaleString('pt-BR', { day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit', second: '2-digit' }) + '</td>' +
                                        '</tr>';
                                });

                                tableContent += '</tbody>' +
                                    '</table>';

                                reportDiv.innerHTML = tableContent;
                                reportDiv.hidden = false;

                                var btnsTable = document.querySelectorAll("button[name='btnsTable']");
                                btnsTable.forEach(function(btn){
                                    btn.style.display = 'block';
                                });


                                //Gráfico 1
                                var heading = document.createElement('h4');
                                heading.textContent = '';
                                heading.textContent = 'Cliente';
                                chartContainer1.parentNode.insertBefore(heading, chartContainer1);
                                var ctx = document.getElementById('chartContainer1').getContext('2d');
                                var reservationTypes = [];
                                var reservationCounts = [];
                                var backgroundColors = [];
                                data.forEach(function (reservation) {
                                    var typeIndex = reservationTypes.indexOf(reservation.customer);

                                    if (typeIndex === -1) {
                                        reservationTypes.push(reservation.customer);
                                        reservationCounts.push(1);
                                    } else {
                                        reservationCounts[typeIndex]++;
                                    }
                                });
                                var totalReservation = reservationCounts.reduce(function (a, b) {
                                    return a + b;
                                }, 0);
                                var percentageData = reservationCounts.map(function (count) {
                                    return (count / totalReservation) * 100;
                                });
                                for (let i = 0; i < reservationTypes.length; i++) {
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
                                        labels: reservationTypes,
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
                                heading.textContent = '';
                                heading.textContent = 'N° vaga';
                                chartContainer2.parentNode.insertBefore(heading, chartContainer2);
                                var ctx = document.getElementById('chartContainer2').getContext('2d');
                                var reservationTypes = [];
                                var reservationCounts = [];
                                var backgroundColors = [];
                                data.forEach(function (reservation) {
                                    var typeIndex = reservationTypes.indexOf(reservation.parking_space_number);

                                    if (typeIndex === -1) {
                                        reservationTypes.push(reservation.parking_space_number);
                                        reservationCounts.push(1);
                                    } else {
                                        reservationCounts[typeIndex]++;
                                    }
                                });
                                var totalReservation = reservationCounts.reduce(function (a, b) {
                                    return a + b;
                                }, 0);
                                var percentageData = reservationCounts.map(function (count) {
                                    return (count / totalReservation) * 100;
                                });
                                for (let i = 0; i < reservationTypes.length; i++) {
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
                                        labels: reservationTypes,
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
                                heading.textContent = '';
                                heading.textContent = 'Mês de reserva';
                                chartContainer3.parentNode.insertBefore(heading, chartContainer3);
                                var ctx = document.getElementById('chartContainer3').getContext('2d');
                                var reservationTypes = [];
                                var reservationCounts = [];
                                var backgroundColors = [];
                                data.forEach(function (reservation) {
                                    var typeIndex = reservationTypes.indexOf(reservation.age);

                                    if (typeIndex === -1) {
                                        reservationTypes.push(reservation.age);
                                        reservationCounts.push(1);
                                    } else {
                                        reservationCounts[typeIndex]++;
                                    }
                                });

                                // Extrair o mês da data de criação de cada cliente
                                var months = data.map(function(reservation) {
                                return new Date(reservation.created_at).getMonth() + 1; // +1 porque os meses em JavaScript começam em 0
                                });

                                // Calcular a contagem de cada mês
                                var monthCounts = [];
                                for (var i = 1; i <= 12; i++) {
                                var count = months.filter(function(month) {
                                    return month === i;
                                }).length;
                                monthCounts.push(count);
                                }

                                // Calcular o total de clientes
                                var totalReservation = monthCounts.reduce(function(a, b) {
                                return a + b;
                                }, 0);

                                // Calcular as porcentagens dos meses
                                var monthPercentages = monthCounts.map(function(count) {
                                return (count / totalReservation) * 100;
                                });

                                // Gerar cores aleatórias para os meses
                                var backgroundColors = [];
                                for (var i = 0; i < 12; i++) {
                                backgroundColors.push(getRandomColor());
                                }

                                // Criar o gráfico de pizza
                                var ctx = document.getElementById('chartContainer3').getContext('2d');
                                var myChart = new Chart(ctx, {
                                type: 'pie',
                                data: {
                                    labels: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
                                    datasets: [{
                                    data: monthPercentages,
                                    backgroundColor: backgroundColors,
                                    }],
                                },
                                options: {
                                    responsive: true,
                                    plugins: {
                                    datalabels: {
                                        formatter: function(value, context) {
                                        return value.toFixed(2) + '%';
                                        }
                                    }
                                    }
                                },
                                });

                                

                            }               
                            }else{
                                var tableContent = '<table id="table" class="table table-striped">' +
                                                        '<thead>' +
                                                            '<tr>' +
                                                            '<th>Id reserva</th>' +
                                                            '<th>Nome cliente</th>' +
                                                            '<th>N° vaga</th>' +
                                                            '<th>Data reserva</th>' +
                                                            '</tr>' +
                                                        '</thead>' +
                                                        '<tbody>';

                                
                            tableContent += '<tr>' +
                                    '<td>Nenhum registro encontrado</td>' +
                                    '<td>Nenhum registro encontrado</td>' +
                                    '<td>Nenhum registro encontrado</td>' +
                                    '<td>Nenhum registro encontrado</td>' +
                                '</tr>';
                                

                            tableContent += '</tbody>' +
                                '</table>';

                            reportDiv.innerHTML = tableContent;
                            reportDiv.hidden = false;
                            document.getElementById("divCharts").style.display = 'none';
                            let h4s = document.querySelectorAll("h4");
                            h4s.forEach(function(h4){
                                h4.innerHTML = '';
                            });

                            }  
                    }
                    
                }
            )
        }
    });

    /////////////////////////////////////////////////////////////////////////////

    const btnPdf = document.querySelector("#btnPdf");
    btnPdf.addEventListener("click", function(e){
        let table = document.querySelector("#table");
        let tableLines = table.querySelectorAll("tbody tr");

        if(tableLines[0].children[0].innerHTML != 'Nenhum registro encontrado'){

            let type = sel_report.value;
            let initialPeriod = selInitialPeriod.value;
            let finalPeriod = selFinalPeriod.value;

            let btn_ladda = Ladda.create(btnPdf);
            btn_ladda.start();

            var csrf_token = $('meta[name="csrf-token"]').attr('content');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': csrf_token
                }
            });

            $.post(
                "{{ route('report_pdf') }}", {
                    type: type,
                    initialPeriod: initialPeriod,
                    finalPeriod: finalPeriod,
                },//CONTINUAR DAQUI
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
                    link.download = 'relatorio-'+type+'.pdf';
                    link.click();
                    btn_ladda.stop();
                    alert('Recibo gerado.');

                });

        }else{
            alert("Nenhum registro encontrado");
            e.preventDefault();
        }
    });

</script>
