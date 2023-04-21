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
        $('#tb_parking_spaces').DataTable({
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
                    <h1 class="m-0">Gerenciar vagas</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Gerenciar vagas</li>
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

            <h4>Adicionar vagas</h4>
            <div class="container">

                <div style="margin-bottom: 30px;" class="row">
                    <div class="col col-md-2">
                        <label for="">N° de vagas</label>
                        <input class="form-control" id="InpNumberSpaces" type="number" min="1">
                    </div>
                </div>

                <form action="{{ route('AddParkingSpace') }}"  method="post">
                    @csrf
                    <span id="FormParkingSpaces">

                    </span>
                </form>
                
            </div>

            <div style="height:40px;" class="mt-4 mb-4"><!-- spacement --></div>

            <div class="container-fluid">
                <!-- Table -->
                <h4>Vagas</h4>
                <div style="overflow-x: scroll; overflow-y:scroll; height:400px;" id="div_table" class="container">
                    <table class="table table-striped" id="tb_parking_spaces">
                        <thead>
                            <tr>
                                <th style="text-align: center;">Selecionar</th>
                                <th style="text-align: center;">ID vaga</th>
                                <th style="text-align: center;">N° vaga</th>
                                <th style="text-align: center;">Descrição</th>
                                <th style="text-align: center;">Alterar</th>
                                <th style="text-align: center;">Deletar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($contents['parking_spaces'] as $space)
                                <tr>
                                    <td style="text-align: center;"><input type="checkbox" data-id="{{ $space->id }}" name="checks" id=""></td>
                                    <td style="text-align: center;">{{ $space->id }}</td>
                                    <td style="text-align: center;">{{ $space->parking_space_number }}</td>
                                    <td name="td_description" style="text-align: center;">{{ $space->description }}</td>
                                    <td style="text-align: center;"><button class="btn btn-info" name="btns_update" data-id="{{ $space->id }}" type="button">Alterar</button></td>
                                    <td style="text-align: center;">
                                        <form action="{{ route('DeleteParkingSpace', ['id' => $space->id]) }}" method="post">
                                            @csrf
                                            @method('DELETE')
                                            <button onclick="return confirm('Tem certeza que deseja deletar esta vaga?')" class="btn btn-danger" type="submit">Deletar</button>
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

                <div style="margin-top: 20px;">
                    <form action="{{ route('DeleteParkingSpaces') }}" method="post">
                        @csrf
                        <button id="btnDeleteAll"  class="btn btn-primary float-right" type="submit">Deletar selecionados</button>
                        <input type="hidden" name="values" id="space_ids">
                    </form>
                </div>

            </div>
    </section>
    
</div>

<script>
    //MÉTODO RESPONSÁVEL POR DELETAR VAGAS SELECIONADAS
    var btnDeleteAll = document.querySelector("#btnDeleteAll");
    btnDeleteAll.addEventListener("click", function(e){
        if(confirm('Tem certeza que deseja deletar as vagas selecionadas?')){
            let checks = document.querySelectorAll("input[name='checks']");
            let space_ids = document.querySelector("#space_ids");
            space_ids.value = '';
            let i = 0;
            checks.forEach(function(check){
                if(check.checked == true){
                    space_ids.value += check.dataset.id+',';
                    i++;
                }
            });
            if(i == 0){
                alert("Selecione pelo menos uma vaga");
                e.preventDefault();
            }
        }else{
            e.preventDefault();
        }
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

    //MÉTODOS RESPONSÁVEIS POR CRIAR CAMPOS DE ACORDO COM O NÚMERO INFORMADO 
    var InpNumberSpaces = document.getElementById('InpNumberSpaces');
    InpNumberSpaces.addEventListener('keyup', function(e){
        let number = e.target.value;
        let FormParkingSpaces = document.getElementById('FormParkingSpaces');

        FormParkingSpaces.innerHTML = '';

        for(let i = 1; i <= number; i++){
            FormParkingSpaces.innerHTML += '<h6>Vaga '+i+'</h6>'+
                                            '<div class="row">'+
                                                '<div class="col col-md-2">'+
                                                    '<label for="">N° vaga</label>'+
                                                    '<input required name="parking_space_number'+i+'" class="form-control" type="number">'+
                                                '</div>'+
                                                '<div class="col col-md-6">'+
                                                    '<label for="">Descrição</label>'+
                                                    '<input required name="description'+i+'" class="form-control" type="text">'+
                                                '</div>'+
                                            '</div>';
        }
        if(FormParkingSpaces.innerHTML != ''){
            FormParkingSpaces.innerHTML +=  '<input type="hidden" name="number_spaces" value="'+number+'">'+
                                            '<div style="margin-top: 20px;">'+
                                                '<input class="btn btn-primary float-right" value="Adicionar vagas" id="btn_create_service" type="submit">'+
                                            '</div>';
        }
         
    });
    InpNumberSpaces.addEventListener('change', function(e){
        let number = e.target.value;
        let FormParkingSpaces = document.getElementById('FormParkingSpaces');

        FormParkingSpaces.innerHTML = '';

        for(let i = 1; i <= number; i++){
            FormParkingSpaces.innerHTML += '<h6>Vaga '+i+'</h6>'+
                                            '<div class="row">'+
                                                '<div class="col col-md-2">'+
                                                    '<label for="">N° vaga</label>'+
                                                    '<input required name="parking_space_number'+i+'" class="form-control" type="number">'+
                                                '</div>'+
                                                '<div class="col col-md-6">'+
                                                    '<label for="">Descrição</label>'+
                                                    '<input required name="description'+i+'" class="form-control" type="text">'+
                                                '</div>'+
                                            '</div>';
        }
        if(FormParkingSpaces.innerHTML != ''){
            FormParkingSpaces.innerHTML +=  '<input type="hidden" name="number_spaces" value="'+number+'">'+
                                            '<div style="margin-top: 20px;">'+
                                                '<input class="btn btn-primary float-right" value="Adicionar vagas" id="btn_create_service" type="submit">'+
                                            '</div>';
        }
         
    });

    //MÉTODO RESPONSÁVEL POR ALTERAR A DESCRIÇÃO DA VAGA
    var btns_update = document.querySelectorAll("button[name='btns_update']");
    let last_value;
    btns_update.forEach(function(btn){
        btn.addEventListener('click', function(){
            let tr = btn.parentElement.parentElement;
            let td_description = tr.querySelector("td[name='td_description']");
            
            if(td_description.childElementCount == 0){
                last_value = td_description.innerHTML;
                td_description.innerHTML = '<input type="text" value="'+last_value+'" class="form-control">';
                btn.innerText = 'Confirmar alteração';                
            }else{
                if(confirm('Tem ceteza que deseja alterar a descrição desta vaga?')){
                    let id_space = btn.dataset.id;
                    let description = td_description.children[0].value;

                    var csrf_token = $('meta[name="csrf-token"]').attr('content');
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': csrf_token
                        }
                    });

                    $.post(
                        "{{ route('updateSpace') }}", {
                            id_space: id_space,
                            description: description
                        },
                        function(data) {
                            if(data){
                                td_description.innerHTML = td_description.children[0].value;
                                alert('Descrição alterada'); 
                                btn.innerText = 'Alterar';
                            }else{
                                alert('Não alterado');
                            }
                            
                    });
                }else{
                    td_description.innerHTML = last_value;
                    btn.innerText = 'Alterar';
                }
            }
            
            
        });
    });
</script>
