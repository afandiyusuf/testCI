<?php

use Illuminate\Database\Seeder;
use \App\Models\User;
use \App\Permission;
use \App\Role;
use Carbon\Carbon;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
		$developer_super_admin = new Role();
		$developer_super_admin->name         = 'developer-super-admin';
		$developer_super_admin->display_name = 'Developer Super Admin'; // optional
		$developer_super_admin->description  = '1 akun master, dipegang developer'; // optional
		$developer_super_admin->save();

		$user = User::where('email','=','admin@gmail.com')->first();
		$user->attachRole($developer_super_admin);

		$createRole = new Permission();
		$createRole->name = "create-role";
		$createRole->display_name ="create role";
		$createRole->description = "membuat role di admin panel";
		$createRole->save();

		$assignRole = new Permission();
		$assignRole->name = "assign-role";
		$assignRole->description = "assign role to another user";
		$assignRole->display_name = "assign role";
		$assignRole->save();

		$unassignRole = new Permission();
		$unassignRole->name = "unassign-role";
		$unassignRole->display_name = "unassign role";
		$unassignRole->description = "mencabut role terhadap user";
		$unassignRole->save();


		//hak dasar untuk akses page komikng.com
		$adminBasePermission = new Permission();
		$adminBasePermission->name = "base-admin";
		$adminBasePermission->display_name = "base admin permission";
		$adminBasePermission->description = "hak untuk akses admin panel dari komikng.com";
		$adminBasePermission->save();
		$developer_super_admin->attachPermission($createRole);
		$developer_super_admin->attachPermission($assignRole);
		$developer_super_admin->attachPermission($unassignRole);
		$developer_super_admin->attachPermission($adminBasePermission);

		//permission create comic/upload
		$createComic = new Permission();
		$createComic->name = "create-comic";
		$createComic->display_name = "create comic";
		$createComic->description = "membuat komik baru di komikng (upload)";
		$createComic->save();

		//permission publish comic
		$publishComic = new Permission();
		$publishComic->name = "publish-comic";
		$publishComic->display_name = "publish comic";
		$publishComic->description = "publish new comic";
		$publishComic->save();

		//permission unpublish comic
		$unpublishComic = new Permission();
		$unpublishComic->name = "unpublish-comic";
		$unpublishComic->display_name = "unpublish comic";
		$unpublishComic->description = "unpublish comic";
		$unpublishComic->save();

		//manage page comic
		$manageComicPage = new Permission();
		$manageComicPage->name = "manage-comic";
		$manageComicPage->display_name = "manage comic";
		$manageComicPage->description = "memanage page comic, edit delete dsb";
		$manageComicPage->save();

		//edit comic
		$editComic = new Permission();
		$editComic->name = "edit-comic";
		$editComic->display_name = "edit comic";
		$editComic->description = "mengedit data comic, (edit title, author dsb)";
		$editComic->save();

		//delete comic
		$editComic = new Permission();
		$editComic->name = "delete-comic";
		$editComic->display_name = "delete comic";
		$editComic->description = "delete comic";
		$editComic->save();



		//edit sinopsis
		$sinopsisEdit = new Permission();
		$sinopsisEdit->name = "edit-sinopsis";
		$sinopsisEdit->display_name = "edit sinopsis";
		$sinopsisEdit->description = "mengedit sinopsis dari komik";
		$sinopsisEdit->save();

		//edit sinopsis
		$sinopsisPublish = new Permission();
		$sinopsisPublish->name = "publish-sinopsis";
		$sinopsisPublish->display_name = "publish sinopsis";
		$sinopsisPublish->description = "mempublish sinopsis dari komik";
		$sinopsisPublish->save();		
		
		//Unpublish sinopsis
		$sinopsisUnpublish = new Permission();
		$sinopsisUnpublish->name = "unpublish-sinopsis";
		$sinopsisUnpublish->display_name = "unpublish sinopsis";
		$sinopsisUnpublish->description = "unpublish sinopsis dari komik";
		$sinopsisUnpublish->save();

		//Unpublish sinopsis
		$sinopsisDelete = new Permission();
		$sinopsisDelete->name = "delete-sinopsis";
		$sinopsisDelete->display_name = "delete sinopsis";
		$sinopsisDelete->description = "delete sinopsis dari komik";
		$sinopsisDelete->save();


		//edit price
		$priceEdit = new Permission();
		$priceEdit->name = "edit-price";
		$priceEdit->display_name = "edit price comic";
		$priceEdit->description = "edit harga comic";
		$priceEdit->save();

		//publish price
		$pricePublish = new Permission();
		$pricePublish->name = "publish-price";
		$pricePublish->display_name = "publish price comic";
		$pricePublish->description = "publish harga comic";
		$pricePublish->save();

		//unpublish price
		$priceUnpublish = new Permission();
		$priceUnpublish->name = "unpublish-price";
		$priceUnpublish->display_name = "publish price comic";
		$priceUnpublish->description = "publish harga comic";
		$priceUnpublish->save();

		//promo
		$OpenPromo = new Permission();
		$OpenPromo->name = "open-promo";
		$OpenPromo->display_name = "open promo";
		$OpenPromo->description = "open promo's page";
		$OpenPromo->save();


		//promo create
		$CreatePromo = new Permission();
		$CreatePromo->name = "create-promo";
		$CreatePromo->display_name = "create promo";
		$CreatePromo->description = "create promo's items";
		$CreatePromo->save();


		//promo edit
		$EditPromo = new Permission();
		$EditPromo->name = "edit-promo";
		$EditPromo->display_name = "edit promo";
		$EditPromo->description = "edit promo's items";
		$EditPromo->save();

		//publish promo
		$PublishPromo = new Permission();
		$PublishPromo->name = "publish-promo";
		$PublishPromo->display_name = "publish promo";
		$PublishPromo->description = "publish promo's items";
		$PublishPromo->save();

		//unpublish promo
		$UnPublishPromo = new Permission();
		$UnPublishPromo->name = "unpublish-promo";
		$UnPublishPromo->display_name = "unpublish promo";
		$UnPublishPromo->description = "unpublish promo's items";
		$UnPublishPromo->save();

		//delete promo
		$DeletePromo = new Permission();
		$DeletePromo->name = "delete-promo";
		$DeletePromo->display_name = "delete promo";
		$DeletePromo->description = "delete promo's page";
		$DeletePromo->save();

		//comment open
		$openComment = new Permission();
		$openComment->name = "open-comment";
		$openComment->display_name = "open comment";
		$openComment->description = "open comment's page";
		$openComment->save();

		$editComment = new Permission();
		$editComment->name = "edit-comment";
		$editComment->display_name = "edit comment";
		$editComment->description = "edit comment's page";
		$editComment->save();

		$unpublishComment = new Permission();
		$unpublishComment->name = "unpublish-comment";
		$unpublishComment->display_name = "unpublish comment";
		$unpublishComment->description = "unpublish comment";
		$unpublishComment->save();

		$publishComment = new Permission();
		$publishComment->name = "publish-comment";
		$publishComment->display_name = "publish comment";
		$publishComment->description = "publish comment";
		$publishComment->save();

		$deleteComment = new Permission();
		$deleteComment->name = "delete-comment";
		$deleteComment->display_name = "delete comment";
		$deleteComment->description = "delete comment";
		$deleteComment->save();


		$OpenUserAccount = new Permission();
		$OpenUserAccount->name = "open-user";
		$OpenUserAccount->display_name = "open user";
		$OpenUserAccount->description = "open user management page";
		$OpenUserAccount->save();

		$CreateUserAccount = new Permission();
		$CreateUserAccount->name = "create-user";
		$CreateUserAccount->display_name = "create user";
		$CreateUserAccount->description = "create user management page";
		$CreateUserAccount->save();

		$EditUserAccount = new Permission();
		$EditUserAccount->name = "edit-user";
		$EditUserAccount->display_name = "edit user";
		$EditUserAccount->description = "edit user management page";
		$EditUserAccount->save();

		$EditSubScribtionAccount = new Permission();
		$EditSubScribtionAccount->name = "editsub-user";
		$EditSubScribtionAccount->display_name = "edit subscription user";
		$EditSubScribtionAccount->description = "edit subscription user";
		$EditSubScribtionAccount->save();

		$PublishUser = new Permission();
		$PublishUser->name = "publish-user";
		$PublishUser->display_name = "publish user";
		$PublishUser->description = "publish user";
		$PublishUser->save();

		$DeleteUser = new Permission();
		$PublishUser->name = "delete-user";
		$PublishUser->display_name = "delete user";
		$PublishUser->description = "delete user";
		$PublishUser->save();


		$OpenReport = new Permission();
		$OpenReport->name = "open-report";
		$OpenReport->display_name = "open report";
		$OpenReport->description = "open report";
		$OpenReport->save();

		$DownloadReport = new Permission();
		$DownloadReport->name = "download-report";
		$DownloadReport->display_name = "download report";
		$DownloadReport->description = "download report keuangan";
		$DownloadReport->save();

		$UpdateStatusReport = new Permission();
		$UpdateStatusReport->name = "update-report";
		$UpdateStatusReport->display_name = "update report";
		$UpdateStatusReport->description = "update status report";
		$UpdateStatusReport->save();
	}
}