<?php

namespace App\Data\Models;

use App\Data\Models\BaseModel;

class ClientRedirectUri extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'client_id', 'redirect_uri'
    ];

    /**
     * Links to the client for the redirect uri
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}