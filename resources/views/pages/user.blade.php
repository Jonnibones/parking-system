<!-- SET CSRF TOKEN  -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Usuário</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Usuário</li>
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

            <h4>Dados do usuário</h4>
            <div class="container">

                <div style="margin-bottom: 30px;" class="row">
                    <div class="col col-md-2">
                        <label for="">Id</label>
                        <input class="form-control" value="{{ $contents['user']->id; }}" type="text" readonly>
                    </div>
                    <div class="col col-md-5">
                        <label for="">Nome</label>
                        <input class="form-control" value="{{ $contents['user']->name; }}" type="text" readonly>
                    </div>
                    <div class="col col-md-5">
                        <label for="">E-mail</label>
                        <input class="form-control" value="{{ $contents['user']->email; }}" type="text" readonly>
                    </div>
                </div>
                
            </div>

            <div style="height:40px;" class="mt-4 mb-4"><!-- spacement --></div>
    </section>
    
</div>


