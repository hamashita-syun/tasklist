<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     */


public function index()
{
    // 認証済みユーザーのチェック
    if (\Auth::check()) {
        // 現在のユーザーのタスクのみを取得
        $tasks = Task::where('user_id', auth()->id())->get();


        return view('tasks.index', [
            'tasks' => $tasks,
        ]);
    }

    return view('welcome');
}
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $task = new Task;

        return view('tasks.create', [
            'task' => $task,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
public function store(Request $request)
{

    $request->validate([
        'status' => 'required|max:10',
        'content' => 'required|max:255',
    ]);


    $task = new Task;

    $task->status = $request->input('status'); // ステータスを設定
    $task->content = $request->input('content'); // タスク内容を設定

    // 現在のユーザーのIDを設定
    if (auth()->check()) {
        $task->user_id = auth()->id(); // ユーザーが認証されている場合、ユーザーIDを設定
    } else {
        // エラーハンドリング: 未認証の場合
        return redirect()->back()->withErrors(['user' => 'User must be logged in']);
    }

    $task->save();

    return redirect('/');
}

    /**
     * Display the specified resource.
     */
public function show(string $id)
{
    $task = Task::where('id', $id)
        ->where('user_id', auth()->id())
        ->firstOrFail(); 

    return view('tasks.show', [
        'task' => $task,
    ]);
}

    /**
     * Show the form for editing the specified resource.
     */
public function edit(string $id)
{

    $task = Task::where('id', $id)
        ->where('user_id', auth()->id())
        ->firstOrFail(); 

    return view('tasks.edit', [
        'task' => $task,
    ]);
}

    /**
     * Update the specified resource in storage.
     */

public function update(Request $request, string $id)
{
    $request->validate([
        'status' => 'required|max:10', 
        'content' => 'required|max:255', 
    ]);

    $task = Task::where('id', $id)
        ->where('user_id', auth()->id())
        ->firstOrFail(); 

    $task->status = $request->status;
    $task->content = $request->content;
    $task->save();

    return redirect('/');
}

    /**
     * Remove the specified resource from storage.
     */
public function destroy(string $id)
{

    $task = Task::where('id', $id)
        ->where('user_id', auth()->id())
        ->firstOrFail(); // ユーザーが所有するタスクのみを取得


    $task->delete();
    return redirect('/');
}
}
