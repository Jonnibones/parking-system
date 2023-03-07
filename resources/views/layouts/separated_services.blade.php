
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

    @if(session('success'))
      <div class="alert alert-success">{{session('success')}}</div>
    @endif

    <!-- Main content -->
    <section class="content">
      <div style="padding: 20px;" class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <!-- Form-->
        <div class="container">
          
          <form action="{{ route('AddSeparatedService')}}" id="form" method="post">
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
                <label for="">Vaga</label>
                <select required class="form-control" name="id_parking_space" id="">
                  <option selected disabled value="">Selecione uma vaga</option>
                  @foreach ($contents['spaces'] as $space )
                      <option value="{{ $space->id }}">{{ $space->parking_space_number .' - '.$space->description }}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div style="margin-top: 20px;">
              
              <input class="btn btn-primary float-right" value="Criar serviço" id="btn_create_service" type="submit">
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
                <th style="text-align: center;">ID serviço</th>
                <th style="text-align: center;">Vaga</th>
                <th style="text-align: center;">Cliente</th>
                <th style="text-align: center;">Nome motorista</th>
                <th style="text-align: center;">N° habilitação</th>
                <th style="text-align: center;">Placa do carro</th>
                <th style="text-align: center;">Marca</th>
                <th style="text-align: center;">Modelo</th>
                <th style="text-align: center;">Cor</th>
                <th style="text-align: center;">Tipo de serviço</th>
                <th style="text-align: center;">Data/Horário de entrada</th>
                <th style="text-align: center;">Data/Horário de saída</th>
                <th style="text-align: center;">Valor</th>
                <th style="text-align: center;">Status</th>
                <th style="text-align: center;">Usuário</th>
                <th style="text-align: center;">Finalizar serviço</th>
                <th style="text-align: center;">Gerar recibo</th>
              </tr>
            </thead>
            <tbody> 
              @foreach ( $contents['services'] as $service )

                @if(trim($service->status) == 'Finalizado')
                  @php 
                    $color = '#98FB98';
                  @endphp
                @else
                  @php 
                    $color = '';
                  @endphp
                @endif

                <tr style="background-color: {{$color}};">
                  <td style="text-align: center;" id="id_service">{{ $service->id }}</td>
                  <td style="text-align: center;">{{ $service->space_number.' - '.$service->space_description }}</td>
                  <td style="text-align: center;" name="is_clients">{{ isset($service->id_customer) ? 'Sim' : 'Não' }}</td>
                  <td style="text-align: center;">{{ $service->driver_name }}</td>
                  <td style="text-align: center;">{{ $service->driving_license_number }}</td>
                  <td style="text-align: center;">{{ $service->license_plate_number }}</td>
                  <td style="text-align: center;">{{ $service->vehicle_brand }}</td>
                  <td style="text-align: center;">{{ $service->vehicle_model }}</td>
                  <td style="text-align: center;">{{ $service->vehicle_color }}</td>
                  <td style="text-align: center;">{{ $service->service_type }}</td>
                  <td style="text-align: center;" name="entry_times">{{ date('d/m/Y H:i:s', strtotime($service->entry_time)) }}</td>
                  <td style="text-align: center;" name="departure_times">
                    @if($service->departure_time)
                      {{ date('d/m/Y H:i:s', strtotime($service->departure_time)) }}
                    @else
                      --:--
                    @endif
                  </td>
                  <td style="text-align: center;">
                    @if($service->value)
                      {{ $service->value }}
                    @else
                      --:--
                    @endif
                  </td>
                  <td style="text-align: center;">
                    @if($service->status == 'Em andamento')
                      {{ $service->status }}<br>
                      <iframe src="https://giphy.com/embed/l3q2IYN87QjIg51kc" width="30" height="30" frameBorder="0" class="giphy-embed" allowFullScreen></iframe><p><a href="#"></a></p>
                    @else
                      {{ $service->status }}<br>
                      <i style="color:#00FF00" class="ion-checkmark-circled"></i>
                    @endif
                  </td>
                  <td style="text-align: center;">{{ $service->user_name }}</td>
                  <td style="text-align: center;">
                    @if($service->status == 'Em andamento')
                      <button name="btns_finish" class="btn btn-primary">Finalizar serviço</button>
                    @else
                      <button name="btns_finish" title="Serviço finalizado" disabled class="disabled btn btn-success">Serviço finalizado</button>
                    @endif
                  </td>
                  <td style="text-align: center;"><button name="btns_generate_receipt" class="btn btn-primary">Gerar recibo</button></td>
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
    //MÉTODO REPONSÁVEL POR FINALIZAR SERVIÇO
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
          {id_service: id_service},
          function(data) {
              departure_time.innerHTML = data.departure_time;
              value.innerHTML = data.value+'R$';
              status.innerHTML = data.status+'<br><i style="color:#00FF00" class="ion-checkmark-circled"></i>';
              btn_finish.innerHTML = "Serviço finalizado";
              btn_finish.className = "disabled btn btn-success";
              btn_finish.title = "Serviço finalizado";
              btn_finish.disabled = true;
              tr.style.backgroundColor = '#98FB98';
              alert('Serviço finalizado.')
          });
        }
      });
    });

    //MÉTODO REPONSÁVEL POR FAZER DOWNLOAD DO RECIBO EM PDF
    var btns_generate_receipt = document.getElementsByName('btns_generate_receipt');
    btns_generate_receipt.forEach(btn_generate_receipt => {
      btn_generate_receipt.addEventListener('click', function(){
        if(confirm('Tem certeza que deseja gerar o recibo para este serviço?')){
          let tr = btn_generate_receipt.parentElement.parentElement;

          let id_service = tr.children[0].innerHTML;

          var csrf_token = $('meta[name="csrf-token"]').attr('content');
          $.ajaxSetup({
              headers: {
                  'X-CSRF-TOKEN': csrf_token
              }
          });
          
          $.post(
          "{{route('generate_receipt')}}", 
          {id_service: id_service,},
          function(data) {
            var byteCharacters = atob(data);
            var byteNumbers = new Array(byteCharacters.length);
            for (var i = 0; i < byteCharacters.length; i++) {
              byteNumbers[i] = byteCharacters.charCodeAt(i);
            }
            var byteArray = new Uint8Array(byteNumbers);
            var blob = new Blob([byteArray], {type: 'application/pdf'});
            var link = document.createElement('a');
            link.href = window.URL.createObjectURL(blob);
            link.download = 'recibo-servico-' + id_service + '.pdf';
            link.click();
            alert('Recibo gerado.');
              
          });
        }
      });
    }); 
  </script>

