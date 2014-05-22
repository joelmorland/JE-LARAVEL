@extends('layouts.master')

@section('content')
    <div class="container">
        <div id="search">
            <form id="search" action="/restaurants" method="post">
                <input type="text" name="areacode" id="areacode" />
                <label for="areacode">Postcode</label>
                <input type="submit" value="Search" id="search" />
            </form>
        </div>
        <div id="restaurants"></div>
    </div>
    
    <div id="dialog">
        <div class="body"></div>
    </div>
    <div id="loader"></div>
@stop