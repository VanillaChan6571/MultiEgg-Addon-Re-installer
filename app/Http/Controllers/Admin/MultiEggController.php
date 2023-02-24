<?php

namespace Pterodactyl\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use DB;
use DateTime;
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
        if(!MultiEggController::verify()) {
                echo "<strong>FAIL</strong> You either edited a file or updated too quickly!</br></br>If you changed a file, rerun the script. If you updated too quickly, just wait 5 minutes at max. If you still see this error after that, please let a member of Administration know.";
        }
        return $this->view->make('admin.multiegg.index', [
            'version' => $this->version,
            'keys'=>$keys,
            'plan'=>MultiEggController::getPlan(),
            'client'=>MultiEggController::getIssuee(),
            'business'=>MultiEggController::getBusiness(),
            'expires'=>MultiEggController::prettyDate(),
            'brand'=>MultiEggController::getBrand(),
            'key_decrypted'=>MultiEggController::getKey(),
            'valid'=>MultiEggController::isValid(),
            'feature_perms'=>MultiEggController::getFeaturePerms(),
            'rawkeys'=>MultiEggController::getRawKeys(),
            'information'=>MultiEggController::getData(),
            'game_toggles'=>MultiEggController::getToggles(),
            'global_settings'=>MultiEggController::getGlobalSettings()
        ]);
    }

    public function support()
    {
        if(!MultiEggController::verify()) {
                echo "<strong>FAIL</strong> You either edited a file or updated too quickly!</br></br>If you changed a file, rerun the script. If you updated too quickly, please wait at max 5 minutes. If you still see this error after that, please contact a member of Administration.";
        }
        return $this->view->make('admin.multiegg.support', [
            'valid'=>MultiEggController::isValid(),
            'version'=>$this->version,
            'global_settings'=>MultiEggController::getGlobalSettings()
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


            $build = "https://api.multiegg.xyz/keyInfo.php?v1={$cKey[0]}&v2={$lKey[0]}";
            $res = Http::timeout(30)->get($build)->object();
            Cache::put('multiegg_license', $res, now()->addMinutes(60));
        }
        return Cache::get('multiegg_license');
    }

    public function getGlobalSettings() {
        if(!Cache::has('multiegg_globalsettings')){
            $url = "https://api.multiegg.xyz/addon/settings.json";
            $res = Http::timeout(30)->get($url)->object();
        
            $settings = new \stdClass();
            $settings->mass_disable = $res->mass_disable;
            $settings->latest_version = $res->latest_version;
            $settings->current_version = "1.3.0";
            Cache::put('multiegg_globalsettings', $settings, now()->addMinutes(5));
        }
        return Cache::get('multiegg_globalsettings');
    }

    public function getRawKeys() {
        $cKey = DB::table('multiegg')
            ->where('id', '=', '1')
            ->pluck('confirm_key');

        $lKey = DB::table('multiegg')
            ->where('id', '=', '1')
            ->pluck('license_key');

        $cRaw = $cKey[0];
        $lRaw = $lKey[0];

        $rawKeys = [ "confirm_key" => $cRaw, "license_key" => $lRaw ];
        return $rawKeys;

    }

    public function getData() {
        $data = [
            "domain" => MultiEggController::getDomain(),
            "watermark" => MultiEggController::getWatermark(),
            "email" => MultiEggController::getEmail(),
            "discord" => MultiEggController::getDiscord(),
        ];
        return $data;
    }

    public function isValid() {
        if(MultiEggController::keyValid() && MultiEggController::domainValid() && MultiEggController::timeValid()){
            return "Active";
        }
        return "Inactive";
    }

    public function prettyDate() {
	$now = new DateTime();
	$future_date = new DateTime(MultiEggController::getExpiry());

	$interval = $future_date->diff($now);
	$interval_pretty = $interval->format("(%a day(s), %h hour(s))");

	$expiry = strtotime(MultiEggController::getExpiry());
	$expiry_pretty = date('M d Y', $expiry);
        return $expiry_pretty." ".$interval_pretty;
    }

    public function timeValid() {
        if(MultiEggController::keyValid()) {
            $date = new DateTime(MultiEggController::getExpiry());
            $now = new DateTime();
            if($date < $now){
                return false;
            }
            return true;
        }
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
            return $data->client->issuee;
        }
        return "ERROR";
    }

    public function getBusiness() {
        if(MultiEggController::keyValid()){
            $data = MultiEggController::getLicenseDetails();
            return $data->client->issuee_business;
        }
        return "ERROR";
    }

    public function getExpiry() {
        if(MultiEggController::keyValid()){
            $data = MultiEggController::getLicenseDetails();
            return $data->keyinfo->expires;
        }
        return "ERROR";
    }

    public function getPlan() {
        if(MultiEggController::keyValid()){
            $data = MultiEggController::getLicenseDetails();
            return $data->keyinfo->plan;
        }
        return "ERROR";
    }

    public function getBrand() {
        if(MultiEggController::keyValid()){
            $data = MultiEggController::getLicenseDetails();
            return $data->custombranding->whitelabel;
        }
        return "ERROR";
    }

    public function getDomain() {
        if(MultiEggController::keyValid()){
            $data = MultiEggController::getLicenseDetails();
            return $data->custombranding->valid_domain;
        }
        return "ERROR";
    }

    public function getEmail() {
        if(MultiEggController::keyValid()){
            $data = MultiEggController::getLicenseDetails();
            return $data->custombranding->email;
        }
        return "ERROR";
    }

    public function getDiscord() {
        if(MultiEggController::keyValid()){
            $data = MultiEggController::getLicenseDetails();
            return $data->custombranding->discord;
        }
        return "ERROR";
    }

    public function getWatermark() {
        if(MultiEggController::keyValid()){
            $data = MultiEggController::getLicenseDetails();
            return $data->custombranding->whitelabel;
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


    public function getToggles() {
        if(MultiEggController::keyValid()){
            $data = MultiEggController::getLicenseDetails();

            $toggles = new \stdClass();
            $toggles->mcj = $data->game_toggles->h_mcj;
            $toggles->mcb = $data->game_toggles->h_mcb;
            $toggles->mcp = $data->game_toggles->h_mcp;
            $toggles->dsb = $data->game_toggles->h_dsb;
            $toggles->vcs = $data->game_toggles->h_vcs;

            return $toggles;
        }
        return "ERROR";
    }

    public function getFeaturePerms(){
        if(MultiEggController::keyValid()){
            $data = MultiEggController::getLicenseDetails();

            $toggles = new \stdClass();
            $toggles->watermark = $data->feature_perms->watermark;
            $toggles->discord_link = $data->feature_perms->discord_link;
            $toggles->game_toggles = $data->feature_perms->game_toggles;
            $toggles->emails = $data->feature_perms->change_email;
            return $toggles;
        }
        $toggles = new \stdClass();
        $toggles->watermark = 0;
        $toggles->discord_link = 0;
        $toggles->game_toggles = 0;
        $toggles->emails = 0;
        return $toggles;
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

    public function clearCache() {
        if(Cache::has('multiegg_license')){
                Cache::forget('multiegg_license');
        }
        MultiEggController::getLicenseDetails();
        $this->alert->success('License Key Info Successfully Updated')->flash();
        return redirect()->route('admin.multiegg.index');
    }

    public function verify() {
        $model = exec('echo $(curl -s https://api.multiegg.xyz/addon/SHAs/model.sha) | sha256sum -c');
        $contr = exec('echo $(curl -s https://api.multiegg.xyz/addon/SHAs/controller.sha) | sha256sum -c');
        $index = exec('echo $(curl -s https://api.multiegg.xyz/addon/SHAs/index.sha) | sha256sum -c');
        $suppo = exec('echo $(curl -s https://api.multiegg.xyz/addon/SHAs/support.sha) | sha256sum -c');
        $navba = exec('echo $(curl -s https://api.multiegg.xyz/addon/SHAs/navbar.sha) | sha256sum -c');
        $notic = exec('echo $(curl -s https://api.multiegg.xyz/addon/SHAs/notice.sha) | sha256sum -c');
        if(!str_contains($model, 'OK') or !str_contains($contr, 'OK') or !str_contains($index, 'OK') or !str_contains($suppo, 'OK') or !str_contains($navba, 'OK') or !str_contains($notic, 'OK')) {
                $result = false;
        } else {
                $result = true;
        }
        return $result;
    }

}
