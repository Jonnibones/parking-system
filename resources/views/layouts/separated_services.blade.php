

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Serviço avulso</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Serviço avulso</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div style="margin-left: 5%" class="row">
          
          <form action="{{ route('AddSeparatedService')}}" method="post">
            @csrf
            <h4 style="margin-top: 30px; margin-botton:20px;">Dados motorista</h4>
            <div class="row" >
                <div class="col col-md-6">
                    <label for="">Nome motorista</label>
                    <input name="driver_name" class="form-control" type="text">
                </div>
                <div class="col col-md-6">
                    <label for="">N° habilitação</label>
                    <input name="driving_license_number" class="form-control" type="text">
                </div>
                
            </div>
            <h4 style="margin-top: 30px; margin-botton:20px;">Dados veículo</h4>
            <div class="row" >
                <div class="col col-md-3">
                    <label for="">Modelo</label>
                    <input name="vehicle_model" class="form-control" type="text">
                </div>
                <div class="col col-md-3">
                    <label for="">Placa</label>
                    <input name="license_plate_number" class="form-control" type="text">
                </div>
                <div class="col col-md-3">
                    <label for="">Marca</label>
                    <input name="vehicle_brand" class="form-control" type="text">
                </div>
                <div class="col col-md-3">
                    <label for="">Cor</label>
                    <input name="vehicle_color" class="form-control" type="text">
                </div>
            </div>
            <div style="margin-top: 20px;">
                <input class="btn btn-primary float-right" value="Criar serviço" type="submit">
            </div>
          </form>
          <!-- ./col -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>

