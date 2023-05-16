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
        $('#tb_customers').DataTable({
            paginate:false,
        });
    });

    //Máscara de input
    $(document).ready(function(){
      $('#inp_phone_number').mask('(00) 00000-0000');
    });
</script>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Gerenciar clientes</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Gerenciar clientes</li>
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

            <form action="{{ route('AddCustomer') }}"  method="post">
                @csrf
                <h4>Cliente</h4>
                <div class="container-fluid" style="margin-bottom: 80px;">
                    <div style="margin-bottom: 30px;" class="row">
                        <div class="col col-md-4">
                            <label for="">Nome</label>
                            <input required class="form-control" name="name" type="text">
                        </div>
                        <div class="col col-md-4">
                            <label for="">N° habilitação</label>
                            <input required class="form-control" name="driving_license_number" type="text">
                        </div>
                        <div class="col col-md-4">
                            <label for="">E-mail</label>
                            <input required class="form-control" name="email" type="email">
                        </div>
                    </div>
                    <div style="margin-bottom: 30px;" class="row">
                        <div class="col col-md-6">
                            <label for="">N° Telefone/Celular</label>
                            <input required class="form-control" name="phone" id="inp_phone_number" type="text">
                        </div>
                        <div class="col col-md-6">
                            <label for="">Endereço</label>
                            <textarea class="form-control" name="address" id="" cols="30" rows="5"></textarea>
                        </div>
                    </div>
                        
                    

                </div>

                <h4>Veículo</h4>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col col-md-3">
                            <label for="">Placa</label>
                            <input class="form-control" name="license_plate_number" id="license_plate_number" type="text">
                        </div>
                        <div class="col col-md-3">
                            <label for="">Marca</label>
                            <input class="form-control" name="brand" id="brand">
                        </div>
                        <div class="col col-md-3">
                            <label for="">Modelo</label>
                            <input class="form-control" name="model" id="model">
                        </div>
                        <div class="col col-md-3">
                            <label for="">Cor</label>
                            <input class="form-control" name="color" id="color">
                        </div>
                    </div>
                </div>
                <div style="margin-top: 20px;">
                    <input onclick="return confirm('Tem certeza que deseja adicionar este cliente?')" 
                    class="btn btn-primary float-right" value="Adicionar cliente" id="btn_create_customer" 
                    type="submit">
                </div>                                  
            </form>

            <div style="height:40px;" class="mt-4 mb-4"><!-- spacement --></div>

            <div class="container-fluid">
                <!-- Table -->
                <h4>Lista de clientes</h4>
                <div style="overflow-x: scroll; overflow-y:scroll; height:400px;" id="div_table" class="container">
                    <table class="table table-striped" id="tb_customers">
                        <thead>
                            <tr>
                                <th style="text-align: center;">Selecionar</th>
                                <th style="text-align: center;">ID cliente</th>
                                <th style="text-align: center;">Nome</th>
                                <th style="text-align: center;">N° habilitação</th>
                                <th style="text-align: center;">E-mail</th>
                                <th style="text-align: center;">Contato</th>
                                <th style="text-align: center;">Endereço</th>
                                <th style="text-align: center;">Veículos</th>
                                <th style="text-align: center;">Alterar</th>
                                <th style="text-align: center;">Deletar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($contents['customers'] as $customer)
                                <tr>
                                    <td style="text-align: center;"><input type="checkbox" data-id="{{ $customer['id'] }}" name="checks" id=""></td>
                                    <td style="text-align: center;">{{ $customer['id'] }}</td>
                                    <td name="td_name" style="text-align: center;">{{ $customer['name'] }}</td>
                                    <td name="td_license" style="text-align: center;">{{ $customer['driving_license_number'] }}</td>
                                    <td name="td_email" style="text-align: center;">{{ $customer['email'] }}</td>
                                    <td name="td_phone" style="text-align: center;">{{ $customer['phone'] }}</td>
                                    <td name="td_address" style="text-align: center;">{{ $customer['address'] }}</td>
                                    <td name="td_vehicles" style="text-align: center;">
                                        <details>
                                            <summary style="cursor:pointer; color:blue;">Ver veículos</summary>
                                            <ul class="list-group">
                                                @if (isset($customer['vehicles'][0]))
                                                    @foreach ($customer['vehicles'] as $vehicle)
                                                        <li class="list-group-item"> <small>{{$vehicle['model']}}</small></li>
                                                    @endforeach
                                                @else
                                                   <small>{{'Cliente sem veículos'}}</small> 
                                                @endif
                                            </ul>
                                            <small style="cursor:pointer; color:blue;"><a href="{{route('customers_vehicles', ['id' => $customer["id"]])}}">Adicionar um veículo à este cliente</a></small>
                                        </details>
                                        
                                    </td>
                                    <td style="text-align: center;"><button class="btn btn-info" name="btns_update" data-id="{{ $customer['id'] }}" type="button">Alterar</button></td>
                                    <td style="text-align: center;">
                                        <form action="{{ route('DeleteCustomer', ['id' => $customer['id']]) }}" method="post">
                                            @csrf
                                            @method('DELETE')
                                            <button onclick="return confirm('Tem certeza que deseja deletar este cliente?')" class="btn btn-danger" type="submit">Deletar</button>
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
                    <form action="{{ route('DeleteCustomers') }}" method="post">
                        @csrf
                        <button id="btnDeleteAll"  class="btn btn-primary float-right" type="submit">Deletar selecionados</button>
                        <input type="hidden" name="values" id="customers_ids">
                    </form>
                </div>

            </div>
    </section>
    
</div>

<script>

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

    
    
</script>
