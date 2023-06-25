<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $contents['title'] }}</title>


    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome version  5.15.4 -->
    <link rel="stylesheet"
        href="<?php echo $base_url; ?>/vendor/almasaeed2010/adminlte/plugins/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet"
        href="<?php echo $base_url; ?>/vendor/almasaeed2010/adminlte/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <!-- iCheck -->
    <link rel="stylesheet"
        href="<?php echo $base_url; ?>/vendor/almasaeed2010/adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- JQVMap -->
    <link rel="stylesheet" href="<?php echo $base_url; ?>/vendor/almasaeed2010/adminlte/plugins/jqvmap/jqvmap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo $base_url; ?>/vendor/almasaeed2010/adminlte/dist/css/adminlte.min.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet"
        href="<?php echo $base_url; ?>/vendor/almasaeed2010/adminlte/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- Daterange picker -->
    <link rel="stylesheet"
        href="<?php echo $base_url; ?>/vendor/almasaeed2010/adminlte/plugins/daterangepicker/daterangepicker.css">
    <!-- summernote -->
    <link rel="stylesheet"
        href="<?php echo $base_url; ?>/vendor/almasaeed2010/adminlte/plugins/summernote/summernote-bs4.min.css">
    <!-- Datatables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.2/css/jquery.dataTables.min.css">
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>  
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.11.3/datatables.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $.extend(true, $.fn.dataTable.defaults, {
                "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.4/i18n/pt-BR.json"
                }
            });
        });
    </script>
    <!-- Ladda -->
    <link href="https://cdn.jsdelivr.net/npm/ladda@2.0.1/dist/ladda.min.css" rel="stylesheet" />
    <script src="<?php echo $base_url; ?>/resources/js/spin.min.js"></script>
    <script src="<?php echo $base_url; ?>/resources/js/ladda.min.js"></script>

</head>

<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">