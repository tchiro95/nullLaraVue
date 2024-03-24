<?php

namespace App\Http\Controllers;

use App\Models\TchiroTest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Inertia\Inertia;


class TchiroTestController extends Controller
{
  public function index()
  {
    $tchiroTest = new TchiroTest();
    $datas = $tchiroTest->all();
    return Inertia::render('User/TchiroTest/TchiroIndex', ['datas' => $datas]);
  }
  public function create()
  {
    return Inertia::render('User/TchiroTest/TchiroCreate');
  }
  public function show($id)
  {
    $data = TchiroTest::findOrFail($id);

    return Inertia::render('User/TchiroTest/TchiroShow', ['id' => $id, 'data' => $data]);
  }
  public function store(Request $request)
  {
    // validationはここでもかけられる。
    $request->validate([
      'title' => ['required', 'max:20'],
      'content' => ['required',],
    ]);

    $tchiroTest = new TchiroTest();

    $tchiroTest->title = $request->title;
    $tchiroTest->content = $request->content;
    $tchiroTest->save();
    //to_routeでも行ける。結局上のindexメソッドに飛ぶので、inertia::renderはindexが担当する
    return to_route('user.tchirotest.index')->with([
      'message' => '登録しました',
    ]);
    //Inertiaでも。
    // return Inertia::render('TchiroTest/TchiroIndex');
  }

  public function delete($id)
  {
    $data = TchiroTest::findOrFail($id);
    $data->delete();
    return to_route('user.tchirotest.index')->with([
      'message' => '削除しました',
    ]);
  }
}
