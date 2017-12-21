<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i < 50000; $i++) {
            $sponsors = User::where('username', '!=', 'company')->select('_id')->get();
            $sponsorIds = [];
            foreach ($sponsors as $sponsor) {
                $sponsorIds[] = $sponsor->_id;
            }
            $sponsorId = array_random($sponsorIds);
            $sponsor = User::find($sponsorId);

            $username = mb_strtolower(str_random(10) . $i);
            $fname = str_random(10);
            $sname = str_random(10);

            $response = User::createUser($sponsor, [
                'username' => $username,
                'email' => $username . '@mail.mm',
                'fname' => mb_strtoupper(mb_substr($fname, 0, 1)) . mb_substr($fname, 1),
                'sname' => mb_strtoupper(mb_substr($sname, 0, 1)) . mb_substr($sname, 1),
                'phone' => $i . rand(586998, 99999999999),
                'password' => str_random(6),
                'finPassword' => str_random(6),
                'country' => 'ua'
            ]);
        }
    }

}
