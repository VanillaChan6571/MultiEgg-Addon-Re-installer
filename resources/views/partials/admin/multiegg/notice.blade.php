use app\Http\Controllers\MultiEggController;

@section('multiegg::notice')
    @if($valid == 'Inactive')
        <div class="row">
            <div class="col-xs-12">
                <div class="alert alert-danger">
                    Your license key is invalid. Please make sure you have an active plan/key. If you belive this is wrong, contact support.
                </div>
            </div>
        </div>
    @endif
@endsection
