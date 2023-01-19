<?php

namespace Pterodactyl\Models;

    // please no remove, the other tabs are literally identical to yours, and if you tryna access a higher plan, the api will literally smack you across the face and break your panel.
    // sorry there, but welp, I guess that's one way to prevent pervs from tryna see the code nonsense
    //
    // this is your warning. Support will not play nice >:(

class MultiEgg extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'multiegg';

    /**
     * Fields that are mass assignable.
     */
    protected $fillable = [
        'updated_at',
        'confirm_key',
        'license_key',
    ];

    public static array $validationRules = [
        'updated_at' => 'required|date',
        'confirm_key' => 'required|string',
        'license_key' => 'required|string',
    ];
}
