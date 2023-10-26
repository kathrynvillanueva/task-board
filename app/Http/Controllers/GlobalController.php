<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Carbon\Carbon;
use App\Project;
use App\Member;

use Bouncer;
use Auth;
use DB;

class GlobalController extends Controller
{
    public function getAbilities() {
      try{
        $user = [];
        $abilities = [];
        $role = [];

        if (Auth::check()) {
          Bouncer::tables([
            'abilities' => 'employee_abilities',
            'assigned_roles' => 'employee_assigned_roles',
            'permissions' => 'employee_permissions',
            'roles' => 'employee_roles'
          ]);
          $user = Auth::guard('web')->user();
          $abilities = $user->getAbilities()->pluck('name'); 
          $role = Bouncer::role()->where('id', $user['employee_role_id'])->pluck('title');
        }
        
        return compact('role', 'abilities', 'user');
      }
      catch(\Exception $ex) {
        return response(['message' => env('APP_DEBUG') ? $ex->getMessage() : config('global.failed-get')], 500);
      }
    }

    public function getModule(Request $request) {
      try{
        $project_id = Project::where('code', $request->code)->pluck('id')->first();
        $members = Member::where('project_id', $project_id)->get();

        return $members;
      }
      catch(\Exception $ex) {
        return response(['message' => env('APP_DEBUG') ? $ex->getMessage() : config('global.failed-get')], 500);
      }
    }
}
