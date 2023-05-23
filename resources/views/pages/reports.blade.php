<!-- SET CSRF TOKEN  -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<style>
    /* Fixed-header style 
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
    */
</style>

<script>
    //Datatables initialisation 
    /*
    $(document).ready(function() {
        $('#tb_customers_vehicles').DataTable({
            paginate:false,
        });
    });
    */
</script>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Relatórios</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Relatórios</li>
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

            <h4>Relatórios</h4>
            <div class="container">

                <form action="#"  method="post">
                    @csrf
                    <div style="margin-bottom: 30px;" class="row">
                        <div class="col col-md-4">
                            <label for="">Tipo</label>
                            <select class="form-control" id="sel_report">
                            </select>
                        </div>
                        <div class="col col-md-4">
                            <label for="">Período</label>
                            <select class="form-control" id="sel_period">
                            </select>
                        </div>
                        <input onclick="return confirm('Tem certeza que deseja adicionar este veículo para este cliente?')" 
                        class="btn btn-primary btn-sm float-right" value="Buscar" id="btnSearch" 
                        type="submit">
                    </div>
                </form>

            </div>

            <div style="height:40px;" class="mt-4 mb-4"><!-- spacement --></div>

            <div class="container">
                <!-- Table -->
                <h4>Resultado relatório</h4>
                <div style="overflow-x: scroll; overflow-y:scroll; height:400px;" id="div_table" class="container">
                    
                </div>

            </div>
        </div>
    </section>
    
</div>

<script>
    
    
</script>
