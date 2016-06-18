<?php //This is the Auth Clients seeder

use Faker\Generator as Faker;
use Illuminate\Database\Seeder;

class OauthClientsSeeder extends Seeder
{
    /**
     * @var  Faker
     */
    private $faker;

    /**
     * OauthClientsSeeder constructor.
     *
     * @param  Faker $faker
     */
    function __construct(Faker $faker)
    {
        $this->faker = $faker;
    }

    /**
     * Run the database seeds.
     *
     * @return  void
     */
    public function run()
    {
        //Clean the databases
        \DB::table('oauth_clients')->delete();
        \DB::table('oauth_client_endpoints')->delete();

        // Create several oauth clients
        \DB::transaction(function () {
            for ($i = 1; $i<5; $i++)
            {
                $client_id = md5(time() . $i);

                DB::insert('insert into oauth_clients (id, name, secret) values (?, ?, ?)',
                        [$client_id, $this->faker->name, $this->faker->text($maxNbChars = 40)]);

                DB::insert('insert into oauth_client_endpoints (client_id, redirect_uri) values (?, ?)',
                        [$client_id, $this->faker->url]);
            }
        });

    }
}