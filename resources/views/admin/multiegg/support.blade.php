    // please no remove, the other tabs are literally identical to yours, and if you tryna access a higher plan, the api literally smack you across t>
    // sorry there, but welp, I guess that's one way to prevent pervs from tryna see the code nonsense
    //
    // this is your warning. Support will not play nice >:(

<style>
  .warning {color:#F02E30; font-weight:bold; font-size:12px;}
</style>

@extends('layouts.admin')
@include('partials/admin.multiegg.nav', ['activeTab' => 'support', 'valid' => $valid, 'global_settings' => $global_settings])

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


<div class="row" style="padding-left: 14px; padding-right: 14px">
    <div class="sol-sm-8">
        <form action="https://api.multiegg.xyz/contactSupport.php" method="POST">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Support Form</h3>
            </div>

            <div class="box-body">
                <div class="form-group">
                    {{ Form::label('email', 'Email') }}
                    {{ Form::email('email', '', array('class' => 'form-control')) }}
                </div>
                <div class="form-group">
                    {{ Form::label('subject', 'Subject') }}
                    {{ Form::text('subject', 'I need help with...', array('class' => 'form-control')) }}
                </div>
                <div class="form-group">
                    {{ Form::label('body', 'Description') }}
                    <textarea name="body" class="form-control" rows="5"></textarea>
                </div>
                <div>
                    <textarea class="form-control" rows="2" readonly>By submitting this form, you consent for your panel Domain, IP, Addon Version, and other addons / license details to be shared with our support team. (We will get back to you within 3 business days)</textarea>
                </div>
                <div class="form-group">
                    {{ Form::submit('Send', array('class' => 'btn btn-smm btn-primary pull-right')) }}
                </div>
            </div>

        </div>
        </form>
    </div>
</div>


@endsection
