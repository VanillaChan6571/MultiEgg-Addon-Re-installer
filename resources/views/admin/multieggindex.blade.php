    // please no remove, the other tabs are literally identical to yours, and if you tryna access a higher plan, the api literally smack you across t>
    // sorry there, but welp, I guess that's one way to prevent pervs from tryna see the code nonsense
    //
    // this is your warning. Support will not play nice >:(

use app\Http\Controllers\MultiEggController;

@extends('layouts.admin')
@include('partials/admin.multiegg.nav', ['activeTab' => 'overview', 'valid' => $valid])

@section('title')
    MultiEgg
@endsection

@section('content-header')
    <h1>MultiEgg Overview</h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.index') }}">Admin</a></li>
        <li class="active">MultiEgg</li>
    </ol>
@endsection

@section('content')
@yield('multiegg::nav')
<div class="row">
        <div class="col-sm-6">
            <form action="/admin/multiegg/edit" method="POST">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">License</h3>
                </div>
                    @foreach($keys as $key)
                    <div class="box-body">
                        <div class="form-group">
                            <label class="form-label">Confirm Key</label>
                            <textarea name="confirm_key" class="form-control" rows="3">{{ $key->confirm_key }}</textarea>
                    </div>
                        <div class="form-group">
                            <label class="form-label">License Key</label>
                            <textarea name="license_key" class="form-control" rows="3">{{ $key->license_key }}</textarea>
                        </div>
                    </div>
                    @endforeach
                    <div class="box-footer">
                        {!! csrf_field() !!}
                        <button class="btn btn-sm btn-primary pull-right" name="_method" value="POST" type="submit">Save</button>
                    </div>
            </form>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="box
                @if($valid != "Inactive")
                    box-success
                @else
                    box-danger
                @endif
            ">
                <div class="box-header with-border">
                    <h3 class="box-title">Subscription Information</h3>
                </div>
                <div class="box-body">
                    @if($valid != "Inactive")
                    Decrypted License: <strong>{{ $key_decrypted }}</strong></br>
                    Active Plan: <strong>{{ $plan }}</strong></br>
                    Expires In: <strong>{{ $expires }}</strong></br>
                    Client: <strong>{{ $client }}</strong></br>
                    Client Business: <strong>{{ $business }}</strong></br>
                    Status: <strong>{{ $valid }}</strong></br>
                    @else
                    Status: <strong>{{ $valid }}</strong></br>
                    Probable Causes: <strong>Invalid Domain | Wrong Key</strong></br>
                    @endif
                </div>
            </div>
        </div>
      </div>
    <div class="row">
  <div class="col-xs-6 col-sm-3 text-center">
</div>
</div>
@endsection
