<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
    @section('head')
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
    <title>Just Eat - Joel Morland</title>    
    {{ HTML::style('css/style.css'); }}
    {{ HTML::style('css/ui-lightness/jquery-ui-1.10.4.min.css'); }}
    @show
</head>
<body>
    @yield('content')
    @section('footer_scripts')

    @show
    {{ HTML::script('js/jquery-2.1.0.min.js'); }}
    {{ HTML::script('js/jquery-ui-1.10.4.min.js'); }}   
    {{ HTML::script('js/script.js'); }}  
</body> 
</html>