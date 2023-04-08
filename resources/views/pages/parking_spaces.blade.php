<!-- SET CSRF TOKEN  -->
<meta name="csrf-token" content="{{ csrf_token() }}">


<script>
    //Datatables initialisation 
    $(document).ready(function() {
        $('#tb_parking_spaces').DataTable();
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
                <!-- ./col -->
                <script>
                    var InpNumberSpaces = document.getElementById('InpNumberSpaces');
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

                        FormParkingSpaces.innerHTML +=  '<input type="hidden" name="number_spaces" value="'+number+'">'+
                                                        '<div style="margin-top: 20px;">'+
                                                            '<input class="btn btn-primary float-right" value="Adicionar vagas" id="btn_create_service" type="submit">'+
                                                        '</div>';
                         
                    });
                </script>
            </div>

            <div style="height:40px;" class="mt-4 mb-4"><!-- espaçamento --></div>

            <div style="padding: 20px;" class="container-fluid">

                <!-- Table -->
                <h4>Vagas</h4>
                <div style="margin-top:40px; overflow:scroll" class="container">
                    <table class="table table-striped" id="tb_parking_spaces">
                        <thead>
                            <tr>
                                <th style="text-align: center;">ID vaga</th>
                                <th style="text-align: center;">N° vaga</th>
                                <th style="text-align: center;">Descrição</th>
                                <th style="text-align: center;">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($contents['parking_spaces'] as $spaces)
                                <tr>
                                    <td style="text-align: center;">{{ $spaces->id }}</td>
                                    <td style="text-align: center;">{{ $spaces->parking_space_number }}</td>
                                    <td style="text-align: center;">{{ $spaces->description }}</td>
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

<script></script>
