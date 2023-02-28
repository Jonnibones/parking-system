
  <!-- Datatables initialisation -->
  <script>
    $(document).ready( function () {
      $('#table_sep_services').DataTable();
    } );
  </script>

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
      <div style="padding: 20px;" class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <!-- Form-->
        <div class="container">
          
          <form action="{{ route('AddSeparatedService')}}" method="post">
            @csrf
            <h4 style="margin-top: 30px; margin-botton:20px;">Dados motorista</h4>
            <input type="hidden" name="id_user" value="{{session('user')['id']}}">
            <div class="row" >
                <div class="col col-md-6">
                    <label for="">Nome motorista</label>
                    <input required name="driver_name" class="form-control" type="text">
                </div>
                <div class="col col-md-6">
                    <label for="">N° habilitação</label>
                    <input required name="driving_license_number" class="form-control" type="text">
                </div>
                
            </div>
            <h4 style="margin-top: 30px; margin-botton:20px;">Dados veículo</h4>
            <div class="row" >
                <div class="col col-md-3">
                    <label for="">Modelo</label>
                    <input required name="vehicle_model" class="form-control" type="text">
                </div>
                <div class="col col-md-3">
                    <label for="">Placa</label>
                    <input required name="license_plate_number" class="form-control" type="text">
                </div>
                <div class="col col-md-3">
                    <label for="">Marca</label>
                    <input required name="vehicle_brand" class="form-control" type="text">
                </div>
                <div class="col col-md-3">
                    <label for="">Cor</label>
                    <input required name="vehicle_color" class="form-control" type="text">
                </div>
            </div>
            <h4 style="margin-top: 30px; margin-botton:20px;">Vaga</h4>
            <div class="row" >
              <div class="col col-md-3">
                <select required class="form-control" name="id_parking_space" id="">
                  <option selected disabled value="">Selecione uma vaga</option>
                  @foreach ($contents['spaces'] as $space )
                      <option value="{{ $space->id }}">{{ $space->parking_space_number .' - '.$space->description }}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div style="margin-top: 20px;">
              <label for="">Selecione uma vaga</label>
              <input class="btn btn-primary float-right" value="Criar serviço" type="submit">
            </div>
          </form>
          <!-- ./col -->
        </div>

        <div style="height:40px;" class="mt-4 mb-4">
          <!-- espaçamento -->
        </div>

        <!-- Table -->
        <div style="margin-top:40px; overflow:scroll" class="container">
          <h4>Serviços</h4>
          <table  class="table table-striped" id="table_sep_services">
            <thead>
              <tr>
                <th>Vaga</th>
                <th>Cliente</th>
                <th>Nome motorista</th>
                <th>N° habilitação</th>
                <th>Placa do carro</th>
                <th>Marca</th>
                <th>Modelo</th>
                <th>Cor</th>
                <th>Tipo de serviço</th>
                <th>Data/Horário de entrada</th>
                <th>Data/Horário de saída</th>
                <th>Status</th>
                <th>Usuário</th>
                <th>Ação</th>
              </tr>
            </thead>
            <tbody> 
              @foreach ( $contents['services'] as $service )
                <tr>
                  <td>{{ $service->space_number.' - '.$service->space_description }}</td>
                  <td>{{ isset($service->id_customer) ? 'Sim' : 'Não' }}</td>
                  <td>{{ $service->driver_name }}</td>
                  <td>{{ $service->driving_license_number }}</td>
                  <td>{{ $service->license_plate_number }}</td>
                  <td>{{ $service->vehicle_brand }}</td>
                  <td>{{ $service->vehicle_model }}</td>
                  <td>{{ $service->vehicle_color }}</td>
                  <td>{{ $service->service_type }}</td>
                  <td>{{ date('d/m/Y H:s:i', strtotime($service->entry_time)) }}</td>
                  <td>{{ $service->departure_time }}</td>
                  <td>{{ $service->status }}</td>
                  <td>{{ $service->user_name }}</td>
                  <td><button class="btn btn-primary">Finalizar serviço</button></td>
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

