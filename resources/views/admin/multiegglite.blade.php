    // please no remove, the other tabs are literally identical to yours, and if you tryna access a higher plan, the api literally smack you across t>
    // sorry there, but welp, I guess that's one way to prevent pervs from tryna see the code nonsense
    //
    // this is your warning. Support will not play nice >:(

<style>
  .warning {color:#F02E30; font-weight:bold; font-size:12px;}
</style>
@extends('layouts.admin')
@include('partials/admin.multiegg.nav', ['activeTab' => 'lite', 'valid' => $valid])

{{-- Tab Title --}}
@section('title')
    Lite
@endsection

{{-- Admin Page Title --}}
@section('content-header')
    <h1>Lite Features</h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.index') }}">Admin</a></li>
        <li class="active">MultiEgg Lite</li>
    </ol>
@endsection

{{-- Adds MultiEgg's navbar --}}
@section('content')
@yield('multiegg::nav')

{{-- Lite Features --}}
<div class="row">
        <div class="col-sm-6">
            <form action="/admin/multiegg/edit" method="POST">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">License</h3>
                </div>
                    <div class="box-body">
                        <div class="form-group">
                            <label class="form-label">Change Domain</label>
                            <label class="warning">This can lock you out of this tab if you are not in set domain.</label>
                            <textarea name="confirm_key" class="form-control" rows="1">Domain</textarea>
                    </div>
                        <div class="form-group">
                            <label class="form-label">Change WaterMark</label>
                            <textarea name="license_key" class="form-control" rows="1">Brand</textarea>
                        </div>
                    </div>
                    <div class="box-footer">
                        {!! csrf_field() !!}
                        <button class="btn btn-sm btn-primary pull-right" name="_method" value="POST" type="submit">Save</button>
                    </div>
            </form>
            </div>
        </div>
</div>
@endsection
