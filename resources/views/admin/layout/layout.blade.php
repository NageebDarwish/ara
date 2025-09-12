<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @include('admin.layout.components.style')
    <title>Document</title>
</head>

<body>
    
    @include('admin.layout.components.navbar')
    <div class="container-fluid page-body-wrapper">
    @include('admin.layout.components.sidebar')
    <div class="main-panel">
        <div class="content-wrapper">
            @yield('content')
        </div>
    </div>
    </div>

    @include('admin.layout.components.footer')
    @include('admin.layout.components.js')

</body>

</html>
