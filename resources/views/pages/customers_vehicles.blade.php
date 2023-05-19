<!-- SET CSRF TOKEN  -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<style>
    /* Fixed-header style */
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
</style>

<script>
    //Datatables initialisation 
    $(document).ready(function() {
        $('#tb_customers_vehicles').DataTable({
            paginate:false,
        });
    });
</script>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Gerenciar veículos clientes</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Gerenciar veículos clientes</li>
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

            <h4>Adicionar veículo cliente</h4>
            <div class="container">

                <form action="#"  method="post">
                    @csrf
                    <div style="margin-bottom: 30px;" class="row">
                        <div class="col col-md-6">
                            <label for="">Cliente</label>
                            <select class="form-control" name="id_cliente" id="sel_cliente">
                                @if (isset($contents['id']))
                                    <option selected value="{{$contents['customer']->id}}">{{$contents['customer']->name}}</option>
                                @else
                                    <option selected disabled value="">Selecione um cliente</option>
                                    @foreach ($contents['customers'] as $customer)
                                        <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div id="divVehicle" style="margin-bottom: 30px;" class="row">
                        <div class="col col-md-3">
                            <label for="">Placa</label>
                            <input disabled class="form-control" name="license_plate_number" id="license_plate_number" type="text">
                        </div>
                        <div class="col col-md-3">
                            <label for="">Marca</label>
                            <input disabled class="form-control" name="brand" id="brand">
                        </div>
                        <div class="col col-md-3">
                            <label for="">Modelo</label>
                            <input disabled class="form-control" name="model" id="model">
                        </div>
                        <div class="col col-md-3">
                            <label for="">Cor</label>
                            <input disabled class="form-control" name="color" id="color">
                        </div>
                    </div>
                    <div style="margin-top: 20px;">
                        <input onclick="return confirm('Tem certeza que deseja adicionar este veículo para este cliente?')" 
                        class="btn btn-primary float-right" value="Adicionar veículo" id="btn_create_vehicle" 
                        type="submit">
                    </div>
                </form>

            </div>

            <div style="height:40px;" class="mt-4 mb-4"><!-- spacement --></div>

            <div class="container">
                <!-- Table -->
                <h4>Veículos clientes</h4>
                <div style="overflow-x: scroll; overflow-y:scroll; height:400px;" id="div_table" class="container">
                    <table class="table table-striped" id="tb_customers">
                        <thead>
                            <tr>
                                <th style="text-align: center;">Selecionar</th>
                                <th style="text-align: center;">ID veículo</th>
                                <th style="text-align: center;">Modelo</th>
                                <th style="text-align: center;">Marca</th>
                                <th style="text-align: center;">Cor</th>
                                <th style="text-align: center;">Placa</th>
                                <th style="text-align: center;">Cliente</th>
                                <th style="text-align: center;">Alterar</th>
                                <th style="text-align: center;">Deletar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($contents['vehicles'] as $vehicle)
                                <tr>
                                    <td style="text-align: center;"><input type="checkbox" data-id="{{ $vehicle->id }}" name="checks" id=""></td>
                                    <td style="text-align: center;">{{ $vehicle->id}}</td>
                                    <td name="td_model" style="text-align: center;">{{ $vehicle->model }}</td>
                                    <td name="td_brand" style="text-align: center;">{{ $vehicle->brand }}</td>
                                    <td name="td_color" style="text-align: center;">{{ $vehicle->color }}</td>
                                    <td name="td_plate" style="text-align: center;">{{ $vehicle->license_plate_number }}</td>
                                    <td style="text-align: center;">{{ $vehicle->customer }}</td>
                                    <td style="text-align: center;"><button class="btn btn-info" name="btns_update" data-id="{{ $vehicle->id }}" type="button">Alterar</button></td>
                                    <td style="text-align: center;">
                                        <form action="{{ route('DeleteVehicle', ['id' => $vehicle->id]) }}" method="post">
                                            @csrf
                                            @method('DELETE')
                                            <button onclick="return confirm('Tem certeza que deseja deletar este veículo?')" class="btn btn-danger" type="submit">Deletar</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div style="margin-top: 20px;">
                    <label for="">Marcar todos</label>
                    <input type="checkbox" name="" id="checkAll">
                </div>

                <div style="margin-top: 20px; margin-bottom: 20px;">
                    <form action="{{ route('DeleteVehicles') }}" method="post">
                        @csrf
                        <button id="btnDeleteAll"  class="btn btn-primary float-right" type="submit">Deletar selecionados</button>
                        <input type="hidden" name="values" id="vehicles_ids">
                    </form>
                </div>

            </div>
        </div>
    </section>
    
</div>

<script>
    //Métodos responsáveis por hbilitar inputs
    const sel_cliente = document.querySelector("#sel_cliente");
    const license_plate_number = document.querySelector("#license_plate_number");
    const brand = document.querySelector("#brand");
    const model = document.querySelector("#model");
    const color = document.querySelector("#color");
    sel_cliente.addEventListener("change", function(){
        let cliente = sel_cliente.options[sel_cliente.selectedIndex].value;
        if(cliente != ''){
            license_plate_number.disabled = false;
            brand.disabled = false;
            model.disabled = false;
            color.disabled = false;
        }
    });
    if(sel_cliente.value != ''){
        license_plate_number.disabled = false;
        brand.disabled = false;
        model.disabled = false;
        color.disabled = false;
    }

    
    //MÉTODO RESPONSÁVEL POR ALTERAR OS DADOS DO VEÍCULO
    var btns_update = document.querySelectorAll("button[name='btns_update']");
    let last_value_model;
    let last_value_brand;
    let last_value_color;
    let last_value_plate;
    btns_update.forEach(function(btn){
        btn.addEventListener('click', function(){
            let tr = btn.parentElement.parentElement;
            let td_model = tr.querySelector("td[name='td_model']");
            let td_brand = tr.querySelector("td[name='td_brand']");
            let td_color = tr.querySelector("td[name='td_color']");
            let td_plate = tr.querySelector("td[name='td_plate']");
            
            if(td_model.childElementCount == 0){

                last_value_model = td_model.innerHTML;
                last_value_brand = td_brand.innerHTML;
                last_value_color = td_color.innerHTML;
                last_value_plate = td_plate.innerHTML;

                td_model.innerHTML = '<input type="text" value="'+last_value_model+'" class="form-control">';
                td_brand.innerHTML = '<input type="text" value="'+last_value_brand+'" class="form-control">';
                td_color.innerHTML = '<input type="text" value="'+last_value_color+'" class="form-control">';
                td_plate.innerHTML = '<input type="text" value="'+last_value_plate+'" class="form-control">';

                btn.innerText = 'Confirmar alteração';                
            }else{
                if(confirm('Tem ceteza que deseja alterar os dados deste veículo?')){
                    let id_vehicle = btn.dataset.id;
                    let model = td_model.children[0].value;
                    let brand = td_brand.children[0].value;
                    let color = td_color.children[0].value;
                    let plate = td_plate.children[0].value;

                    var csrf_token = $('meta[name="csrf-token"]').attr('content');
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': csrf_token
                        }
                    });

                    $.post(
                        "{{ route('updateVehicle') }}", {
                            id_vehicle: id_vehicle,
                            model: model,
                            brand: brand,
                            color: color,
                            plate: plate
                        },
                        function(data) {
                            if(data){
                                td_model.innerHTML = td_model.children[0].value;
                                td_brand.innerHTML = td_brand.children[0].value;
                                td_color.innerHTML = td_color.children[0].value;
                                td_plate.innerHTML = td_plate.children[0].value;
                                
                                alert('Dados alterados'); 
                                btn.innerText = 'Alterar';
                            }else{
                                alert('Não alterado');
                            }
                            
                    });
                }else{
                    td_model.innerHTML = last_value_model;
                    td_brand.innerHTML = last_value_brand;
                    td_color.innerHTML = last_value_color;
                    td_plate.innerHTML = last_value_plate;
                    
                    btn.innerText = 'Alterar';
                }
            }
            
            
        });
    });

    

    //MÉTODO RESPONSÁVEL POR MARCAR/DESMARCAR CHECKBOXES
    var checkAll = document.querySelector("#checkAll");
    checkAll.addEventListener('click', function(){
        let checks = document.querySelectorAll("input[name='checks']");
        if(this.checked == true){
            checks.forEach(function(check){
                check.checked = true;
            });
            this.previousElementSibling.innerHTML = 'Desmarcar todos';
        }else{
            checks.forEach(function(check){
                check.checked = false;
            });
            this.previousElementSibling.innerHTML = 'Marcar todos';
        }
    });

    
    //MÉTODO RESPONSÁVEL POR DELETAR VEÍCULOS SELECIONADOS
    var btnDeleteAll = document.querySelector("#btnDeleteAll");
    btnDeleteAll.addEventListener("click", function(e){
        if(confirm('Tem certeza que deseja deletar os veículos selecionados?')){
            let checks = document.querySelectorAll("input[name='checks']");
            let vehicles_ids = document.querySelector("#vehicles_ids");
            vehicles_ids.value = '';
            let i = 0;
            checks.forEach(function(check){
                if(check.checked == true){
                    vehicles_ids.value += check.dataset.id+',';
                    i++;
                }
            });
            if(i == 0){
                alert("Selecione pelo menos um veículo");
                e.preventDefault();
            }
        }else{
            e.preventDefault();
        }
    });
    

    
    
</script>
