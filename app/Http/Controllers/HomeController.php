<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\User;
use App\Role;
use App\Permission;


class HomeController extends Controller
{

    public function index()
    {
        return User::all();
    }


    /*
    | Thêm quyền cho user.
    | User id `1` có vai trò `owner`
    | http://api.com/api/users/1/roles/owner
    */
    public function attachUserRole($userId, $role)
    {

        $user = User::findOrFail($userId);

        $roleId = Role::where('name', $role)->first();

        $user->roles()->attach($roleId);

        return $user;
    }


    /*
    | Kiểm tra xem User này vai trò gì
    | User id `1` có vai trò là gì
    | http://api.com/api/users/1/roles
    */
    public function getUserRole($userId){
        return User::find($userId)->roles;
    }


    /*
    | Thêm quyền hạn cho vai trò
    | Admin thì có những quyền hạn gì
    | http://api.com/api/role/permission/add
    */
    public function attachPermission(Request $request){
        $parametters = $request->only('permission', 'role');

        $permissionParam = $parametters['permission'];
        $roleParam = $parametters['role'];

        $role = Role::where('name', $roleParam)->first();

        $permission = Permission::where('name', $permissionParam)->first();

        $role->attachPermission($permission);

        return $this->response->created();

    }


    /*
    | Gọi quyền hạn của 1 vai trò
    | Kiểm tra xem admin có những quyền gì
    | http://api.com/api/role/owner/permissions
    */
    public function getPermissions($roleParam){
        
        $role = Role::where('name', $roleParam)->first();

        return $this->response->array($role->perms);

    }

}
