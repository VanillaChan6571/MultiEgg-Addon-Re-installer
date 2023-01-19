    // please no remove, the other tabs are literally identical to yours, and if you tryna access a higher plan, the api literally smack you across t>
    // sorry there, but welp, I guess that's one way to prevent pervs from tryna see the code nonsense
    //
    // this is your warning. Support will not play nice >:(

<style>
  .warning {color:#F02E30; font-weight:bold; font-size:12px;}
</style>

@extends('layouts.admin')
@include('partials/admin.multiegg.nav', ['activeTab' => 'support', 'valid' => $valid])

{{-- Tab Title --}}
@section('title')
    Support
@endsection

{{-- Admin Page Title --}}
@section('content-header')
    <h1>Support System</h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.index') }}">Admin</a></li>
        <li class="active">MultiEgg Support</li>
    </ol>
@endsection

{{-- Adds MultiEgg's navabar --}}
@section('content')
@yield('multiegg::nav')

<strong>Unfortunately, this section is not complete. Please use Discord Support Tickets for now.</strong>

@endsection
