<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
      <img src="<?php echo $base_url;?>/vendor/almasaeed2010/adminlte/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">Parking System</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="<?php echo $base_url;?>/vendor/almasaeed2010/adminlte/dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">Usuário: {{session('user')['name']}}</a>
        </div>
      </div>

      <!-- SidebarSearch Form -->
      <div hidden class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li hidden class="nav-item menu-open">
            <a href="#" class="nav-link active">
              <i class="ion-navicon-round"></i>
              <p>
                Serviços
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="./index.html" class="nav-link active">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Dashboard v1</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./index2.html" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Dashboard v2</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./index3.html" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Dashboard v3</p>
                </a>
              </li>
            </ul>
          </li>

          <!--ITENS ATIVOS DO MENU-->
          <li class="nav-header">Início</li>
          <li class="nav-item">
            <a href="{{route('main')}}" class="nav-link">
              <i class="{{ request()->segment(1) == 'main' ? 'fas fa-circle nav-icon' : 'far fa-circle nav-icon' }}"></i>
              <p>Página inicial</p>
            </a>
          </li>

          <li class="nav-header">Menu</li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="ion-clipboard"></i>
              <p>
                Serviços
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{route('services')}}" class="nav-link">
                  <i class="{{ request()->segment(1) == 'services' ? 'fas fa-circle nav-icon' : 'far fa-circle nav-icon' }}"></i>
                  <p>Serviços</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('separated_service')}}" class="nav-link">
                  <i class="{{ request()->segment(1) == 'separated_service' ? 'fas fa-circle nav-icon' : 'far fa-circle nav-icon' }}"></i>
                  <p>Novo serviço avulso</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('customer_service')}}" class="nav-link">
                  <i class="{{ request()->segment(1) == 'customer_service' ? 'fas fa-circle nav-icon' : 'far fa-circle nav-icon' }}"></i>
                  <p>Novo serviço cliente</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="ion ion-model-s"></i>
              <p>
                Vagas
                <i class="fas fa-angle-left right"></i>
                <span class="badge badge-info right">6</span>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{route('parking_spaces')}}" class="nav-link">
                  <i class="{{ request()->segment(1) == 'parking_spaces' ? 'fas fa-circle nav-icon' : 'far fa-circle nav-icon' }}"></i>
                  <p>Gerenciar vagas</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="ion-ios-people-outline"></i>
              <p>
                Clientes
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{route('customers')}}" class="nav-link">
                  <i class="{{ request()->segment(1) == 'customers' ? 'fas fa-circle nav-icon' : 'far fa-circle nav-icon' }}"></i>
                  <p>Gerenciar clientes</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('customers_vehicles')}}" class="nav-link">
                  <i class="{{ request()->segment(1) == 'customers_vehicles' ? 'fas fa-circle nav-icon' : 'far fa-circle nav-icon' }}"></i>
                  <p>Gerenciar veículos clientes</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="ion-compose"></i>
              <p>
                Reservas
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{route('reservations')}}" class="nav-link">
                  <i class="{{ request()->segment(1) == 'reservations' ? 'fas fa-circle nav-icon' : 'far fa-circle nav-icon' }}"></i>
                  <p>Gerenciar reservas</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="ion-compose"></i>
              <p>
                Relatórios
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{route('reports')}}" class="nav-link">
                  <i class="{{ request()->segment(1) == 'reports' ? 'fas fa-circle nav-icon' : 'far fa-circle nav-icon' }}"></i>
                  <p>Gerenciar relatórios</p>
                </a>
              </li>
            </ul>
          </li>
          <!--ITENS ATIVOS DO MENU-->

        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>