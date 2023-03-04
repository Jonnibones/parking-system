
  <!-- SET CSRF TOKEN  -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

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
                <th>ID serviço</th>
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
                <th>Valor</th>
                <th>Status</th>
                <th>Usuário</th>
                <th>Ação</th>
              </tr>
            </thead>
            <tbody> 
              @foreach ( $contents['services'] as $service )
                <tr>
                  <td id="id_service">{{ $service->id }}</td>
                  <td>{{ $service->space_number.' - '.$service->space_description }}</td>
                  <td name="is_clients">{{ isset($service->id_customer) ? 'Sim' : 'Não' }}</td>
                  <td>{{ $service->driver_name }}</td>
                  <td>{{ $service->driving_license_number }}</td>
                  <td>{{ $service->license_plate_number }}</td>
                  <td>{{ $service->vehicle_brand }}</td>
                  <td>{{ $service->vehicle_model }}</td>
                  <td>{{ $service->vehicle_color }}</td>
                  <td>{{ $service->service_type }}</td>
                  <td name="entry_times">{{ date('d/m/Y H:i:s', strtotime($service->entry_time)) }}</td>
                  <td name="departure_times">
                    @if($service->departure_time)
                      {{ date('d/m/Y H:i:s', strtotime($service->departure_time)) }}
                    @else
                      --:--
                    @endif
                  </td>
                  <td>
                    @if($service->value)
                      {{ $service->value }}
                    @else
                      --:--
                    @endif
                  </td>
                  <td>
                    @if($service->status == 'Em andamento')
                      {{ $service->status }}<br>
                      <iframe src="https://giphy.com/embed/l3q2IYN87QjIg51kc" width="30" height="30" frameBorder="0" class="giphy-embed" allowFullScreen></iframe><p><a href="#"></a></p>
                    @else
                      {{ $service->status }}<br>
                      <i class="ion-checkmark-circled"></i>
                    @endif
                  </td>
                  <td>{{ $service->user_name }}</td>
                  <td><button name="btns_finish" class="btn btn-primary">Finalizar serviço</button></td>
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
  
  <script>  
    var btns_finish = document.getElementsByName('btns_finish');
      btns_finish.forEach(btn_finish => {
        btn_finish.addEventListener('click', function(){
          if(confirm('Tem certeza que deseja finalizar este serviço?')){
            let tr = btn_finish.parentElement.parentElement;

            let id_service = tr.children[0].innerHTML;

            let departure_time = tr.children[11];
            let value = tr.children[12];
            let status = tr.children[13];

            var csrf_token = $('meta[name="csrf-token"]').attr('content');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': csrf_token
                }
            });
            
            $.post(
            "{{route('finish_service')}}", 
            {id_service: id_service}, //<ion-icon name="checkmark-circle"></ion-icon>
            function(data) {
                console.log(data);
                departure_time.innerHTML = data.departure_time;
                value.innerHTML = data.value;
                status.innerHTML = data.status+'<br><i class="ion-checkmark-circled"></i>';
                //tr.style.backgroundColor = 'green';
            });
          }
        });
      }); 
  </script>

