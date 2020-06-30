<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use App\Todolist;

class ToDoListController extends Controller
{
    public function sendResponse($data, $message) {

        if (count($data) > 0) {
            $response = [
                'code'    => 200,
                'status'  => true,
                'data'    => $data,
                'message' => $message,
            ];
        }
        else {

            $response = [
                'code'    => 200,
                'status'  => true,
                'data'    => $data,
                'message' => $message,
            ];
        }

    	return response()->json($response, 200);
    }
    //

    public function welcome(Request $request)
    {
        $toDoList = Todolist::where('status', 0)->get();
        return view('welcome')->with('todolist', $toDoList);
    }

    public function showAll(Request $request)
    {
        $toDoList = Todolist::where('status', 1)->get();
        $data  = [
            'status'                     => 200,
            'data'                    => $toDoList
     ];
     return $this->sendResponse($data, "Success");
    }


    public function add(Request $request)
    {
        $chekInToDoList = Todolist::where('task_name', trim($request->myInput))->get();
        if(count($chekInToDoList) > 0)
        {
            $data  = [
                'status'                     => 201,
                'message'                    => "Activity already in To Do List"
         ];
        }
        else
        {
            $todolist = new Todolist();
            $todolist->task_name = trim($request->myInput);
            $todolist->status = 0;
            $todolist->save();
            $data  = [
                'status'                     => 200,
                'message'                    => "Activity successfully added in To Do List"
         ];
        }
        return $this->sendResponse($data, "Success");
    }

    public function complete(Request $request)
    {
        $chekInToDoList = Todolist::where('task_name', trim($request->myInput))->get();
        if($chekInToDoList[0]->status == 0)
            $status = 1;
        else
            $status = 0;

            DB::table('todolists')
            ->where('task_name', trim($request->myInput))
            ->update(['status' => $status]);
        $data  = [
                'status'                     => 200,
                'message'                    => "Status Updated",
                'updatedStatus'              => $status
         ];
         return $this->sendResponse($data, "Success");
    }

    public function delete(Request $request)
    {
        Todolist::where('task_name', trim($request->myInput))->delete();
        $data  = [
                'status'                     => 200,
                'message'                    => "Deleted"
         ];
         return $this->sendResponse($data, "Success");
    }
}
