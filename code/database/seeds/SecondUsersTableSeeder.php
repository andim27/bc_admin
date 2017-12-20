<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class SecondUsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::where('username', '!=', 'main')->where('username', '!=', 'company')->select('_id')->get();
        $userIds = [];
        foreach ($users as $user) {
            $userIds[] = $user->_id;
        }
        $userCount = count($userIds);
        foreach ($userIds as $key => $userId) {
            echo ++$key . '/' . $userCount . "\n";
            if ($user = User::find($userId)) {
                $user->sideToNextUser = $user->sideToNextUser == 1 ? 0 : 1;
                $user->save();
            }
        }
    }
}
