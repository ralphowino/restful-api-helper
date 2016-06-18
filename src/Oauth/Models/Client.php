<?php

namespace App\Data\Models;

use App\Data\Models\User;
use App\Data\Models\BaseModel;

class Client extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'name', 'secret', 'homepage_url', 'description',
    ];

    /**
     * Links to the user that owns the client
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Links to the client's redirect uri
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function redirect_uri()
    {
        return $this->hasOne(ClientRedirectUri::class);
    }
}
