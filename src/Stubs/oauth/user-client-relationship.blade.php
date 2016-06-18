    /**
     * Links to the clients that belong to the user.
     *
     * @return mixed
     */
    public function clients()
    {
        return $this->hasMany(Client::class);
    }