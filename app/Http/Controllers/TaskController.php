<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Input;

use App\Http\Requests\TaskRequest;
use App\Task;
use App\Label;
use DB;

class TaskController extends Controller
{
    public function getTableDatas(Request $request){
      try {
        // $tasks = Task::with('employee')->get();
        $tasks = Task::get();
        return compact('tasks');
      }
      catch(\Exception $ex){
        return response(['message' => env('APP_DEBUG') ? $ex->getMessage() : config('global.failed-get')], 500);
      }
    }

    public function getInitFormDatas(Request $request){
      try{
        $task = collect();
        $images = collect();
        if($request->id > 0)
        {
          $task = Task::with('label')->where('id', $request->id)->first();
          $images = DB::table('tasks_photo_path')->where('task_id', $task->id)->get();
          $task['imgsToDelete'] = [];
        }

        $labels = Label::all();

        return compact('task', 'labels', 'images');
      }
      catch (\Exception $ex){
        return response(['message' => env('APP_DEBUG') ? $ex->getMessage() : config('global.failed-get')], 500);
      }
    }

    public function uploadImage(Request $request) {
      try {
        $file_name = 'hbsiTask_' . str_random(15);
        $file_extension = $request->file('file')->guessExtension();
        $file = $request->file('file')->storeAs('temp', $file_name . '.' . $file_extension, 'local');
        $path_name = $file_name.'.'.$file_extension;
        return compact('path_name', 'file_extension');
      } catch (\Exception $ex) {
        return response(['message' => env('APP_DEBUG') ? $ex->getMessage() : config('global.failed-upload')], 500);
      }
    }

    public function uploadRaw(Request $request) {
      try {
        $file_name = 'hbsiTask_' . str_random(15);
        Storage::put($file_name . '.' . $request->ext, base64_decode($request->src));
        Storage::move($file_name . '.' . $request->ext, '/temp/' . $file_name . '.' . $request->ext);
        return $file_name;
        } catch (\Exception $ex) {
        return response(['message' => env('APP_DEBUG') ? $ex->getMessage() : config('global.failed-upload')], 500);
      }
    }

    public function store(TaskRequest $request){
      try{
        DB::beginTransaction();

        $index = Task::max('ver_index');
        $inputs = $request->all();
        $inputs['ver_index'] = $index + 1;
        unset($inputs['priority'],$inputs['label'],$inputs['employee']);
        $task = Task::create($inputs);
        for ($a = 0; $a < count($inputs['images']); $a++) {
          DB::table('tasks_photo_path')->insert([
            'task_id' => $task->id,
            'field_name' => $inputs['images'][$a]['field'],
            'image_path' => $inputs['images'][$a]['name'],
          ]);
          Storage::move('/temp/' . $inputs['images'][$a]['name'], '/img/' . $inputs['images'][$a]['name']);
        }
        // foreach ($inputs['custom_fields'] as $data) {
        //   DB::table('tasks')->where('id', $task->id)->update([strtolower(str_replace(" ", "_", $data['field_name'])) => $data['field_data']]);
        // }
        // foreach ($inputs['checkboxes'] as $data) {
        //   DB::table('tasks')->where('id', $task->id)->update([strtolower(str_replace(" ", "_", $data['field_name'])) => $data['field_data']]);
        // }
        DB::commit();
        return response(['message' => config('global.success-store'), 'inputs' => $inputs], 200);
      }
      catch (\Exception $ex){
        DB::rollback();
        return response(['message' => env('APP_DEBUG') ? $ex->getMessage() : config('global.failed-store')], 500);
      }
    }

    public function update(TaskRequest $request){
      try{
        DB::beginTransaction();
        $inputs = $request->all();
        unset($inputs['label'],$inputs['priority'],$inputs['employee']);
        $task = Task::find($request->id)->update($inputs);
        $task = Task::find($request->id);
        for ($a = 0; $a < count($inputs['images']); $a++) {
          DB::table('tasks_photo_path')->insert([
            'task_id' => $task->id,
            'field_name' => $inputs['images'][$a]['field'],
            'image_path' => $inputs['images'][$a]['name'],
          ]);
          Storage::move('/temp/' . $inputs['images'][$a]['name'], '/img/' . $inputs['images'][$a]['name']);
        }
        $imgsToDelete = $inputs['imgsToDelete'];
        for ($b = 0; $b < count($imgsToDelete); $b++) {
          $img = DB::table('tasks_photo_path')->where('image_path', $imgsToDelete[$b]['image_path'])->first();
          Storage::delete('/img/'.$img->image_path);
          DB::table('tasks_photo_path')->where('image_path', $img->image_path)->delete();
        }
        foreach ($inputs['custom_fields'] as $data) {
          DB::table('tasks')->where('id', $task->id)->update([strtolower(str_replace(" ", "_", $data['field_name'])) => $data['field_data']]);
        }
        foreach ($inputs['checkboxes'] as $data) {
          DB::table('tasks')->where('id', $task->id)->update([strtolower(str_replace(" ", "_", $data['field_name'])) => $data['field_data']]);
        }
        DB::commit();
        return response(['updated_id' => $task, 'message' => config('global.success-update')], 200);
      }
      catch(\Exception $ex){
        DB::rollback();
        return response(['message' => env('APP_DEBUG') ? $ex->getMessage() : config('global.failed-update')], 500);
      }
    }

    public function destroy(Request $request){
      try{
        $file_names = DB::table('tasks_photo_path')->where('task_id', $request->id)->pluck('image_path');
        for ($a = 0; $a < count($file_names); $a++) {
          Storage::delete('/img/'.$file_names[$a]);
        }
        Task::destroy($request->id);
        return response(['message' => config('global.success-delete')], 200);
      }
      catch(\Exception $ex){
        return response(['message' => env('APP_DEBUG') ? $ex->getMessage() : config('global.failed-delete')], 500);
      }
    }

    public function destroySelected(Request $request){
      try {
        for ($x = 0; $x < count($request->all()); $x++){
          $file_names = DB::table('tasks_photo_path')->where('task_id', $request[$x])->pluck('image_path');
          for ($a = 0; $a < count($file_names); $a++) {
            Storage::delete('/img/'.$file_names[$a]);
          }
        }
        Task::destroy($request->all());
        return response(['message' => config('global.success-mult-delete')], 200);
      }
      catch(\Exception $ex) {
        return response(['message' => env('APP_DEBUG') ? $ex->getMessage() : config('global.failed-mult-delete')], 500);
      }
    }
}
