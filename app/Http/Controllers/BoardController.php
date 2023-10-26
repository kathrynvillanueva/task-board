<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Label;
use App\Task;
use App\Member;
use App\Module;

class BoardController extends Controller
{
    public function getLabels (Request $request) {
      try {
        $labels = Label::with(['task'])->get();
        $tasks = Task::where('label_id', null)->get();
        return compact('labels', 'tasks');
      }
      catch (\Exception $ex) {
        return response(['message' => env('APP_DEBUG') ? $ex->getMessage() : config('global.failed-get')], 500);
      }
    }

    public function getEmployees (Request $request) {
      try {
        $project_id = Project::where('code', $request->code)->pluck('id')->first();
        $employees = Member::where('project_id', $project_id)->with(['employee'])->get();
        return $employees;
      }
      catch (\Exception $ex) {
        return response(['message' => env('APP_DEBUG') ? $ex->getMessage() : config('global.failed-get')], 500);
      }
    }

    public function assignYourself(Request $request) {
      try {
        Task::find($request->id)->update(['employee_id' => $request['employee_id']]);
      }
      catch(\Exception $ex) {
        return response(['message' => env('APP_DEBUG') ? $ex->getMessage() : config('global.failed-update')], 500);
      }
    }

    public function changeAssignee(Request $request) {
      try {
        Task::find($request->id)->update(['employee_id' => $request['employee_id']]);
      }
      catch(\Exception $ex) {
        return response(['message' => env('APP_DEBUG') ? $ex->getMessage() : config('global.failed-update')], 500);
      }
    }

    public function changeStartDate(Request $request) {
      try {
        Task::find($request->id)->update(['start_date' => $request['start_date']]);
      }
      catch(\Exception $ex) {
        return response(['message' => env('APP_DEBUG') ? $ex->getMessage() : config('global.failed-update')], 500);
      }
    }

    public function changeEndDate(Request $request) {
      try {
        Task::find($request->id)->update(['end_date' => $request['end_date']]);
      }
      catch(\Exception $ex) {
        return response(['message' => env('APP_DEBUG') ? $ex->getMessage() : config('global.failed-update')], 500);
      }
    }

    public function changeDueDate(Request $request) {
      try {
        Task::find($request->id)->update(['due_date' => $request['due_date']]);
      }
      catch(\Exception $ex) {
        return response(['message' => env('APP_DEBUG') ? $ex->getMessage() : config('global.failed-update')], 500);
      }
    }

    public function changeLabel(Request $request) {
      try {
        Task::find($request->id)->update(['label_id' => $request['label_id']]);
      }
      catch(\Exception $ex) {
        return response(['message' => env('APP_DEBUG') ? $ex->getMessage() : config('global.failed-update')], 500);
      }
    }

    public function updateHorIndex(Request $request) {
      try {
        for($x = 0; $x < count($request->all()); $x++) {
          Label::find($request[$x]['id'])->update(['hor_index' => $x]);
        }
      }
      catch(\Exception $ex) {
        return response(['message' => env('APP_DEBUG') ? $ex->getMessage() : config('global.failed-update')], 500);
      }
    }

    public function updateVerIndex(Request $request) {
      try {
        for($z = 0; $z < count($request['labels']); $z++) {
          $tasks = $request['labels'][$z]['task'];
          for($a = 0; $a < count($tasks); $a++)
            Task::find($tasks[$a]['id'])->update(['ver_index' => $a, 'label_id' => $request['labels'][$z]['id']]);
          }
        for($y = 0; $y < count($request->tasks['task']); $y++) {
          Task::find($request->tasks['task'][$y]['id'])->update(['ver_index' => $y, 'label_id' => null]);
        }
      }
      catch(\Exception $ex) {
        return response(['message' => env('APP_DEBUG') ? $ex->getMessage() : config('global.failed-update')], 500);
      }
    }

    public function getModuleName (Request $request) {
      try {
        return Module::where('code', $request->id)->pluck('code')->first();
      }
      catch (Exception $ex) {
        return response(['message' => env('APP_DEBUG') ? $ex->getMessage() : config('global.failed-get')], 500);
      }
    }
}
