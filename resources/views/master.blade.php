<?php $base_url = config('app.url');?>

@include('layouts/header')

@include('layouts/navbar', ['base_url' => $base_url])

@include('layouts/sidebar')

@include('layouts/main')

@include('layouts/footer')