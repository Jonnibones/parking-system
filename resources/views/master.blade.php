<?php 
    use Illuminate\Support\Facades\DB;

    $base_url = config('app.url');

    $numberSpacesBadge['Occupied'] = DB::table('parking_spaces')
    ->where('status', '=', 'Ocupado')
    ->count();
    $numberSpacesBadge['Reserved'] = DB::table('parking_spaces')
    ->where('status', '=', 'Reservado')
    ->count();
    $numberSpacesBadge['Liberated'] = DB::table('parking_spaces')
    ->where('status', '=', 'Liberado')
    ->count();

    $badges = [
        'numberSpacesBadge' => $numberSpacesBadge,
    ];

?>

@include('layouts/header')

@include('layouts/navbar', ['base_url' => $base_url])

@include('layouts/sidebar', ['contents' => $badges])

@include('pages/'.$contents['view'])

@include('layouts/footer')