@include('common.header')
@include('common.footer')

<html>
  <head>
    <meta charset="utf-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width">
    <link rel="shortcut icon" href="favicon.ico">
    <link rel="stylesheet" type="text/css" href="css/common.css">
    <title>
      @yield('title')
    </title>
  </head>
  <body>
    @yield('header')
    <div class="grey-block">
      @yield('errors')
      @yield('notifications')
      @yield('content')
    </div>
    @yield('footer')
  </body>
  <script type="text/javascript" src="../js/jquery.min.js"></script>
  <script type="text/javascript" src="../js/common.js"></script>
  @yield('meta')
</html>