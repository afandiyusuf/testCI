<?php

use Illuminate\Database\Seeder;
use \App\Models\User;
use \App\Permission;
use \App\Role;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        

		

  //   	//insert annonymous seeder
    	for( $i = 0 ;$i<10 ; $i++){
	        DB::table('users')->insert([
	            'name' => str_random(10),
	            'email' => str_random(10).'@gmail.com',
	            'password' => bcrypt('secret'),
	            'gender'=> ($i%2),
	            'alamat' =>str_random(20),
	            'no_hp' => 12312312312312,
	            'username' => str_random(20),
	        ]);
	    };


		DB::table('users')->insert([
			'username' => 'ilyas',
			'name' => 'ilyas',
			'email' => 'ilyas@gmail.com',
			'password' => bcrypt('123qweasd'),
			'gender'=> 1,
			'alamat' =>str_random(20),
			'no_hp' => 12312312312312
		]);

		$admin = new User();
		$admin->name = "admin";
		$admin->username = "admin";
		$admin->email = "admin@gmail.com";
		$admin->password = bcrypt("123qweasd");
		$admin->gender = 1;
		$admin->alamat = "alamat";
		$admin->no_hp = 1231231231232;
		$admin->save();

		// $admin_role = new Role();
		// $admin_role->name         = 'admin';
		// $admin_role->display_name = 'Admin'; // optional
		// $admin_role->description  = 'Ini adalah admin'; // optional
		// $admin_role->save();

		// $admin->attachRole($admin_role);

		$this->call(comic_seeder::class);
		$this->call(Comment_seeder::class);
		$this->call(favorite_seeder::class);
		$this->call(Rating_seeder::class);
		$this->call(PageComicSeeder::class);
		$this->call(author_seeder::class);
    	$this->call(RoleAdditionSeeder::class);

    }
}
