<?php

namespace Pterodactyl\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use DB;
use Illuminate\View\View;
use Pterodactyl\Models\MultiEgg;
use Illuminate\View\Factory as ViewFactory;
use Pterodactyl\Http\Controllers\Controller;
use Pterodactyl\Services\Helpers\SoftwareVersionService;
use Prologue\Alerts\AlertsMessageBag;

class MultiEggController extends Controller
{
    /**
     * MultiEggController constructor.
     */
    public function __construct(
        private AlertsMessageBag $alert,
        private SoftwareVersionService $version,
        private ViewFactory $view
    ) {
    }

    /**
     * Return the admin index view.
     */
    public function index(): View
    {
        $keys = DB::select('select * from `multiegg`');
        return $this->view->make('admin.multiegg.index', [
            'version' => $this->version,
            'keys'=>$keys,
            'plan'=>MultiEggController::getPlan(),
            'client'=>MultiEggController::getIssuee(),
            'business'=>MultiEggController::getBusiness(),
            'expires'=>MultiEggController::getExpiry(),
            'brand'=>MultiEggController::getBrand(),
            'key_decrypted'=>MultiEggController::getKey(),
            'valid'=>MultiEggController::isValid()
        ]);
    }

    /**
     * Return the lite view.
     */
    public function lite()
    {

        if(!MultiEggController::allowed("LITE")){
            return MultiEggController::denied();
        }
        return $this->view->make('admin.multiegg.lite', [
            'valid'=>MultiEggController::isValid(),
            'version'=>$this->version
        ]);
    }

    /**
     * Return the plus view.
     */
    public function plus()
    {

        if(!MultiEggController::allowed("PLUS")){
            return MultiEggController::denied();
        }
        return $this->view->make('admin.multiegg.plus', [
            'valid'=>MultiEggController::isValid(),
            'version'=>$this->version
        ]);
    }

    public function pro()
    {

        if(!MultiEggController::allowed("PRO")){
            return MultiEggController::denied();
        }
        return $this->view->make('admin.multiegg.pro', [
            'valid'=>MultiEggController::isValid(),
            'version'=>$this->version
        ]);
    }

    public function support()
    {
        return $this->view->make('admin.multiegg.support', [
            'valid'=>MultiEggController::isValid(),
            'version'=>$this->version
       ]);
    }

    /**
     * Update license settings.
     */
    public function update(MultiEgg $multiegg, Request $request)
    {
        //$multiegg->update([
        //    'confirm_key' => $request->confirm_key,
        //    'license_key' => $request->license_key,
        //]);

        MultiEgg::where('id',1)->update(['confirm_key'=>$request->confirm_key]);
        MultiEgg::where('id',1)->update(['license_key'=>$request->license_key]);

        $this->alert->success('License Key Info Successfully Updated')->flash();

        if(Cache::has('multiegg_license')){
                Cache::forget('multiegg_license');
        }
        MultiEggController::getLicenseDetails();

        return redirect()->route('admin.multiegg.index');
    }

    public function getLicenseDetails() {
        if(!Cache::has('multiegg_license')) {
            $cKey = DB::table('multiegg')
                ->where('id', '=', '1')
                ->pluck('confirm_key');

            $lKey = DB::table('multiegg')
                ->where('id', '=', '1')
                ->pluck('license_key');


            $build = "https://license.multiegg.ml/keyInfo.php?v1={$cKey[0]}&v2={$lKey[0]}";
            $res = Http::get($build)->object();

            Cache::put('multiegg_license', $res, now()->addMinutes(60));
        }
        return Cache::get('multiegg_license');
    }

    public function isValid() {
        if(MultiEggController::keyValid() && MultiEggController::domainValid()){
            return "Active";
        }
        return "Inactive";
    }

    public function keyValid() {
        $data = MultiEggController::getLicenseDetails();
        if(isset($data->key)) {
            return true;
        }
        return false;
    }

    public function getKey() {
        if(MultiEggController::keyValid()){
            $data = MultiEggController::getLicenseDetails();
            return $data->key;
        }
        return "ERROR";
    }

    public function getIssuee() {
        if(MultiEggController::keyValid()){
            $data = MultiEggController::getLicenseDetails();
            return $data->data->issuee;
        }
        return "ERROR";
    }

    public function getBusiness() {
        if(MultiEggController::keyValid()){
            $data = MultiEggController::getLicenseDetails();
            return $data->data->issuee_business;
        }
        return "ERROR";
    }

    public function getExpiry() {
        if(MultiEggController::keyValid()){
            $data = MultiEggController::getLicenseDetails();
            return $data->data->expires;
        }
        return "ERROR";
    }

    public function getPlan() {
        if(MultiEggController::keyValid()){
            $data = MultiEggController::getLicenseDetails();
            return $data->data->plan;
        }
        return "ERROR";
    }

    public function getBrand() {
        if(MultiEggController::keyValid()){
            $data = MultiEggController::getLicenseDetails();
            return $data->data->custom_brand;
        }
        return "ERROR";
    }

    public function getDomain() {
        if(MultiEggController::keyValid()){
            $data = MultiEggController::getLicenseDetails();
            return $data->data->valid_domain;
        }
        return "ERROR";
    }

    public function domainValid() {
        if(MultiEggController::keyValid()){
            Log::info(config('app.url'));
            Log::info(MultiEggController::getDomain());
            if(config('app.url') == MultiEggController::getDomain()){
                return true;
            }
            return false;
        }
        return false;
    }


    public function allowed($area) {
        if(MultiEggController::keyValid()){
            $plan = MultiEggController::getPlan();
            if($area == $plan){ return true; }else{ return false; }
        }
    }
    // please no remove, the other tabs are literally identical to yours, and if you tryna access a higher plan, the api literally smack you across the face and break your panel :(
    // sorry there, but welp, I guess that's one way to prevent pervs from tryna see the code nonsense
    //
    // this is your warning. Support will not play nice >:(
    private function denied() {
        $this->alert->warning("You do not have access to this plan.")->flash();
        return redirect()->back();
    }

}