use app\Http\Controllers\MultiEggController;

<style>
        .extraPadd { padding-left: 100px }
</style>

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
                    Expires: <strong>{{ $expires }}</strong></br>
                    Client: <strong>{{ $client }}</strong></br>
                    Client Business: <strong>{{ $business }}</strong></br>
                    Status: <strong>{{ $valid }}</strong></br>
                    @else
                    Status: <strong>{{ $valid }}</strong></br>
                    Probable Causes: <strong>Invalid Domain | Wrong Key | Expired Key</strong></br>
                    @endif
                </div>
            </div>
        </div>
      </div>
    <div class="row">
    <div class="row" style="padding-left: 12px">
        <div class="col-sm-4">
            <form action="https://api.multiegg.xyz/updateKey.php" method="POST">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">License</h3>
                </div>
                    <div class="box-body">
                        <div class="form-group">
                            <label class="form-label">Identifier Key</label>
                            <textarea name="v1" class="form-control" readonly rows="5">{{ $rawkeys['confirm_key'] }}</textarea>
                        </div>
                        <div class="form-group">
                            <label class="form-label">License Key</label>
                            <textarea name="v2" class="form-control" readonly rows="5">{{ $rawkeys['license_key'] }}</textarea>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Change Domain</label>
                            <label class="warning">This can lock you out of this tab if you are not in set domain.</label>
                            <textarea name="valid_domain" class="form-control" rows="1">{{ $information['domain'] }}</textarea>
                        </div>
                        @if( $feature_perms->watermark )
                        <div class="form-group">
                            <label class="form-label">Change WaterMark</label>
                            <textarea name="custom_brand" class="form-control" rows="1">{{ $information['watermark'] }}</textarea>
                        </div>
                        @endif
                        @if( $feature_perms->emails )
                        <div class="form-group">
                            <label class="form-label">Change Contact Email</label>
                            <textarea name="contact_email" class="form-control" rows="1">{{ $information['email'] }}</textarea>
                        </div>
                        @endif
                        @if( $feature_perms->discord_link )
                        <div class="form-group">
                            <label class="form-label">Change Discord Link</label>
                            <textarea name="discord_link" class="form-control" rows="1">{{ $information['discord'] }}</textarea>
                        </div>
                        @endif
                    </div>
                    <div class="box-footer">
                        {!! csrf_field() !!}
                        <button class="btn btn-sm btn-primary pull-right" name="_method" value="POST" type="submit">Save</button>
                    </div>
            </form>
         </div>
       </div>

        @if( $feature_perms->game_toggles )
        <div class="col-sm-4">
            <form action="https://api.multiegg.xyz/updateGameToggle.php" method="POST">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Game Toggles</h3>
                </div>
                    <div class="box-body">
                        <div class="form-group">
                            <label class="form-label">Identifier Key</label>
                            <textarea name="v1" class="form-control" readonly rows="5">{{ $rawkeys['confirm_key'] }}</textarea>
                        </div>
                        <div class="form-group">
                            <label class="form-label">License Key</label>
                            <textarea name="v2" class="form-control" readonly rows="5">{{ $rawkeys['license_key'] }}</textarea>
                        </div>
                        <div class="form-group">
                            {{ Form::label('mcj', 'Minecraft Java', array('class' => 'form-label')) }}
                            {{ Form::checkbox('mcj', '1', $game_toggles->mcj) }}
                        </div>
                        <div class="form-group">
                            {{ Form::label('mcb', 'Minecraft Bedrock', array('class' => 'form-label')) }}
                            {{ Form::checkbox('mcb', '1', $game_toggles->mcb) }}
                        </div>
                        <div class="form-group">
                            {{ Form::label('mcp', 'Minecraft Proxy', array('class' => 'form-label')) }}
                            {{ Form::checkbox('mcp', '1', $game_toggles->mcp) }}
                        </div>
                        <div class="form-group">
                            {{ Form::label('dsb', 'Discord Bot', array('class' => 'form-label')) }}
                            {{ Form::checkbox('dsb', '1', $game_toggles->dsb) }}
                        </div>
                        <div class="form-group">
                            {{ Form::label('vcs', 'Voice Servers', array('class' => 'form-label')) }}
                            {{ Form::checkbox('vcs', '1', $game_toggles->vcs) }}
                        </div>
                    </div>
                    <div class="box-footer">
                        {!! csrf_field() !!}
                        <button class="btn btn-sm btn-primary pull-right" name="_method" value="POST" type="submit">Save</button>
                    </div>
            </form>
         </div>
       </div>
       @endif
       <div class="col-xs-6 col-sm-3 text-center">
</div>
</div>
@endsection
