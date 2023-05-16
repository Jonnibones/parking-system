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


        <div style="padding: 20px;" class="container-fluid">
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

            <div class="container-fluid">
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

    /*
        //MÉTODO RESPONSÁVEL POR ALTERAR OS DADOS DO CLIENTE
        var btns_update = document.querySelectorAll("button[name='btns_update']");
        let last_value_name;
        let last_value_license;
        let last_value_email;
        let last_value_phone;
        let last_value_address;
        btns_update.forEach(function(btn){
            btn.addEventListener('click', function(){
                let tr = btn.parentElement.parentElement;
                let td_name = tr.querySelector("td[name='td_name']");
                let td_license = tr.querySelector("td[name='td_license']");
                let td_email = tr.querySelector("td[name='td_email']");
                let td_phone = tr.querySelector("td[name='td_phone']");
                let td_address = tr.querySelector("td[name='td_address']");
                
                if(td_name.childElementCount == 0){

                    last_value_name = td_name.innerHTML;
                    last_value_license = td_license.innerHTML;
                    last_value_email = td_email.innerHTML;
                    last_value_phone = td_phone.innerHTML;
                    last_value_address = td_address.innerHTML;

                    td_name.innerHTML = '<input type="text" value="'+last_value_name+'" class="form-control">';
                    td_license.innerHTML = '<input type="text" value="'+last_value_license+'" class="form-control">';
                    td_email.innerHTML = '<input type="email" value="'+last_value_email+'" class="form-control">';
                    td_phone.innerHTML = '<input type="text" value="'+last_value_phone+'" class="form-control">';
                    td_address.innerHTML = '<input type="text" value="'+last_value_address+'" class="form-control">';

                    btn.innerText = 'Confirmar alteração';                
                }else{
                    if(confirm('Tem ceteza que deseja alterar a descrição deste cliente?')){
                        let id_customer = btn.dataset.id;
                        let name = td_name.children[0].value;
                        let license = td_license.children[0].value;
                        let email = td_email.children[0].value;
                        let phone = td_phone.children[0].value;
                        let address = td_address.children[0].value;

                        var csrf_token = $('meta[name="csrf-token"]').attr('content');
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': csrf_token
                            }
                        });

                        $.post(
                            "{{ route('updateCustomer') }}", {
                                id_customer: id_customer,
                                name: name,
                                license: license,
                                email: email,
                                phone: phone,
                                address: address
                            },
                            function(data) {
                                if(data){
                                    td_name.innerHTML = td_name.children[0].value;
                                    td_license.innerHTML = td_license.children[0].value;
                                    td_email.innerHTML = td_email.children[0].value;
                                    td_phone.innerHTML = td_phone.children[0].value;
                                    td_address.innerHTML = td_address.children[0].value;
                                    alert('Dados alterados'); 
                                    btn.innerText = 'Alterar';
                                }else{
                                    alert('Não alterado');
                                }
                                
                        });
                    }else{
                        td_name.innerHTML = last_value_name;
                        td_license.innerHTML = last_value_license;
                        td_email.innerHTML = last_value_email;
                        td_phone.innerHTML = last_value_phone;
                        td_address.innerHTML = last_value_address;
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

        
        //MÉTODO RESPONSÁVEL POR DELETAR CLIENTES SELECIONADOS
        var btnDeleteAll = document.querySelector("#btnDeleteAll");
        btnDeleteAll.addEventListener("click", function(e){
            if(confirm('Tem certeza que deseja deletar os clientes selecionados?')){
                let checks = document.querySelectorAll("input[name='checks']");
                let customers_ids = document.querySelector("#customers_ids");
                customers_ids.value = '';
                let i = 0;
                checks.forEach(function(check){
                    if(check.checked == true){
                        customers_ids.value += check.dataset.id+',';
                        i++;
                    }
                });
                if(i == 0){
                    alert("Selecione pelo menos um cliente");
                    e.preventDefault();
                }
            }else{
                e.preventDefault();
            }
        });
    */

    
    
</script>
