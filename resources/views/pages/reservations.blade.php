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
        $('#tb_reservations').DataTable({
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
                    <h1 class="m-0">Gerenciar reservas</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Gerenciar reservas</li>
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

            <h4>Adicionar reserva</h4>
            <div class="container">

                <form action="{{ route('AddReservation') }}"  method="post">
                    @csrf
                    <div style="margin-bottom: 30px;" class="row">
                        <div class="col col-md-6">
                            <label for="">Cliente</label>
                            <select required class="form-control" name="id_customer" id="sel_customer">
                                <option selected disabled value="">Selecione um cliente</option>
                                @foreach ($contents['customers'] as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col col-md-6">
                            <label for="">Vaga</label>
                            <select required class="form-control" name="id_parking_space" id="sel_space">
                                <option selected disabled value="">Selecione uma vaga</option>
                                @foreach ($contents['spaces'] as $spaces)
                                    <option value="{{ $spaces->id }}">{{ $spaces->space }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div style="margin-top: 20px;">
                        <input onclick="return confirm('Tem certeza que deseja criar esta reserva?')" 
                        class="btn btn-primary float-right" value="Criar reserva" id="btn_create_reservation" 
                        type="submit">
                    </div>
                </form>

            </div>

            <div style="height:40px;" class="mt-4 mb-4"><!-- spacement --></div>

            <div class="container">
                <!-- Table -->
                <h4>Reservas</h4>
                <div style="overflow-x: scroll; overflow-y:scroll; height:400px;" id="div_table" class="container">
                    <table class="table table-striped" id="tb_reservations">
                        <thead>
                            <tr>
                                <th style="text-align: center;">Selecionar</th>
                                <th style="text-align: center;">ID reserva</th>
                                <th style="text-align: center;">Cliente</th>
                                <th style="text-align: center;">Número vaga</th>
                                <th style="text-align: center;">Data/Hora</th>
                                <th style="text-align: center;">Ativa</th>
                                <th style="text-align: center;">Deletar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($contents['reservations'] as $reservation)
                                <tr>
                                    <td style="text-align: center;"><input type="checkbox" data-id="{{ $reservation->id }}" name="checks" id=""></td>
                                    <td style="text-align: center;">{{ $reservation->id}}</td>
                                    <td name="td_customer" style="text-align: center;">{{ $reservation->customer }}</td>
                                    <td name="td_spaceNumber" style="text-align: center;">{{ $reservation->parking_space_number }}</td>
                                    <td name="td_date" style="text-align: center;">{{ date('d-m-Y H:i:s', strtotime($reservation->created_at)) }}</td>
                                    <td name="td_active" style="text-align: center;">{{ $reservation->active ? 'Sim' : 'Não'}}</td>
                                    <td style="text-align: center;">
                                        <form action="{{ route('DeleteReservation', ['id' => $reservation->id]) }}" method="post">
                                            @csrf
                                            @method('DELETE')
                                            <button onclick="return confirm('Tem certeza que deseja deletar esta reserva?')" name="btnDel" class="btn btn-danger" type="submit">Deletar</button>
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
                    <form action="{{ route('DeleteReservations') }}" method="post">
                        @csrf
                        <button id="btnDeleteAll"  class="btn btn-primary float-right" type="submit">Deletar selecionados</button>
                        <input type="hidden" name="values" id="reservations_ids">
                    </form>
                </div>

            </div>
        </div>
    </section>
    
</div>

<script>


    //MÉTODO RESPONSÁVEL POR MARCAR/DESMARCAR CHECKBOXES
    var checkAll = document.querySelector("#checkAll");
    checkAll.addEventListener('click', function(){
        let checks = document.querySelectorAll("input[name='checks']");
        if(this.checked == true){
            checks.forEach(function(check){
                if(check.disabled != true){
                    check.checked = true;
                }
            });
            this.previousElementSibling.innerHTML = 'Desmarcar todos';
        }else{
            checks.forEach(function(check){
                check.checked = false;
            });
            this.previousElementSibling.innerHTML = 'Marcar todos';
        }
    });

    //MÉTODO RESPONSÁVEL POR DELETAR RESERVAS SELECIONADAS
    var btnDeleteAll = document.querySelector("#btnDeleteAll");
    btnDeleteAll.addEventListener("click", function(e){
        if(confirm('Tem certeza que deseja deletar as reservas selecionadas?')){
            let checks = document.querySelectorAll("input[name='checks']");
            let reservations_ids = document.querySelector("#reservations_ids");
            reservations_ids.value = '';
            let i = 0;
            checks.forEach(function(check){
                if(check.checked == true){
                    reservations_ids.value += check.dataset.id+',';
                    i++;
                }
            });
            if(i == 0){
                alert("Selecione pelo menos uma reserva");
                e.preventDefault();
            }
        }else{
            e.preventDefault();
        }
    });

    const td_active = document.querySelectorAll("td[name='td_active']");
    td_active.forEach(function(td){
        let tr = td.parentElement;
        let check = tr.querySelector('input[name="checks"]');
        let btnDel = tr.querySelector('button[name="btnDel"]');
        if(td.innerHTML == 'Não'){
            check.disabled = true;
            btnDel.className = 'disabled btn btn-danger';
        }
    });
    
    
</script>
