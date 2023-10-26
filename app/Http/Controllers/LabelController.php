<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LabelRequest;

use App\Label;

class LabelController extends Controller
{
    public function getTableDatas(Request $request) {
      try {
        $labels = Label::all();
        $max_index = Label::max('hor_index');
        return compact('labels', 'max_index');
      }
      catch(\Exception $ex){
        return response(['message' => env('APP_DEBUG') ? $ex->getMessage() : config('global.failed-get')], 500);
      }
    }

    public function store(LabelRequest $request){
      try {
        $label = Label::create($request->all());
        return response(['id' => $label->id, 'message' => config('global.success-store')], 200);
      }
      catch(\Exception $ex){
        return response(['message' => env('APP_DEBUG') ? $ex->getMessage() : config('global.failed-store')], 500);
      }
    }

    public function update(LabelRequest $request){
      try{
        $label = Label::find($request->id)->update($request->all());
        return response(['message' => config('global.success-update')], 200);
      }
      catch(\Exception $ex){
        return response(['message' => env('APP_DEBUG') ? $ex->getMessage() : config('global.failed-update')], 500);
      }
    }

    public function destroy(Request $request) {
      try {
        Label::destroy($request->id);
        return response(['message' => config('global.success-delete')], 200);
      }
      catch(\Exception $ex) {
        return response(['message' => env('APP_DEBUG') ? $ex->getMessage() : config('global.failed-delete')], 500);
      }
    }

    public function destroySelected(Request $request) {
      try {
        Label::destroy($request->all());
        return response(['message' => config('global.success-mult-delete')], 200);
      }
      catch(\Exception $ex) {
        return response(['message' => env('APP_DEBUG') ? $ex->getMessage() : config('global.failed-mult-delete')], 500);
      }
    }
}
