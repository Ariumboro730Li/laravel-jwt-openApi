<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TodoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        $todos = Todo::all();
        return response()->json([
            'status' => 'success',
            'todos' => $todos,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "title" => "required|string|min:4|max:255",
            "description" => "required|string|min:10|max:255",
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $todo = Todo::create([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Todo created successfully',
            'todo' => $todo,
        ]);
    }

    public function show($id)
    {
        $todo = Todo::find($id);
        if ($todo) {
            return response()->json([
                'status' => 'success',
                'todo' => $todo,
            ]);
        } else {
            return response()->json([
                'message' => 'Todo is not EXISTS',
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            "title" => "required|string|min:4|max:255",
            "description" => "required|string|min:10|max:255",
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $todo = Todo::find($id);
        $todo->title = $request->title;
        $todo->description = $request->description;
        $todo->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Todo updated successfully',
            'todo' => $todo,
        ]);
    }

    public function destroy($id)
    {
        $todo = Todo::find($id);
        if ($todo) {
            $todo->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Todo deleted successfully',
                'todo' => $todo,
            ]);
        } else {
            return response()->json([
                'message' => 'Todo is not EXISTS',
            ], 404);
        }
    }
}
