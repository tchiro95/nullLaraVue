<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Owner;
use Inertia\Inertia;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;


class OwnersController extends Controller
{

  public function __construct()
  {
    $this->middleware('auth:admin');
  }
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $owners = Owner::select('id', 'name', 'email', 'created_at')->paginate(3);

    //mapでdateフォーマットを整えている。vueだとbladeのようにcarbonが使えないので

    //下のやり方だと、pagenateのデータがいかない。getなら下のやり方でOK

    // $owners = $owners->map(function ($owners) {
    //   return [
    //     'id' => $owners->id,
    //     'name' => $owners->name,
    //     'email' => $owners->email,
    //     'created_at' => Carbon::parse($owners->created_at)->diffForHumans(),
    //     // 他のフォーマットが必要な場合はここに追加
    //   ];
    // });
    return Inertia::render('Admin/Owners/Index', [
      'owners' => $owners,
    ]);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    return Inertia::render('Admin/Owners/Create');
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $request->validate([
      'name' => 'required|string|max:255',
      'email' => 'required|string|email|max:255|unique:' . Owner::class,
      'password' => ['required', 'string', 'min:8', 'confirmed', Rules\Password::defaults()],
    ]);

    $owner = Owner::create([
      'name' => $request->name,
      'email' => $request->email,
      'password' => Hash::make($request->password),
    ]);
    return to_route('admin.owners.index')->with([
      'message' => '登録しました',
      'status' => 'message'
    ]);
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    $owner = Owner::findOrFail($id);
    return Inertia::render('Admin/Owners/Edit', [
      'owner' => $owner,
    ]);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
  {
    //
    $request->validate([
      'name' => 'required|string|max:255',
      'email' => 'required|string|email|max:255|unique:' . Owner::class,
      'password' => ['required', 'string', 'min:8', 'confirmed', Rules\Password::defaults()],
    ]);

    $owner = Owner::findOrFail($id);
    $owner->name = $request->name;
    $owner->email = $request->email;
    $owner->password = Hash::make($request->password);
    $owner->save();

    return to_route('admin.owners.index')->with([
      'message' => '更新しました',
      'status' => 'message'
    ]);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    //

    Owner::findOrFail($id)->delete();
    //ソフトデリートなので、期限切れオーナーのところに行く。
    return to_route('admin.owners.index')->with([
      'message' => 'オーナー情報を削除しました',
      'status' => 'alert'
    ]);
  }

  // 以下期限切れオーナーの一覧と削除 期限切れは、更新してないオーナー。更新する、永久削除の二択ができるようにしておく。
  public function expiredOwnerIndex()
  {
    $expiredOwners = Owner::onlyTrashed()->get();

    return Inertia::render('Admin/Owners/ExpiredOwners', [
      'owners' => $expiredOwners,
    ]);
  }
  // ハードデリート
  public function expiredOwnerDestroy($id)
  {
    Owner::onlyTrashed()->findOrFail($id)->forceDelete();
    return to_route('admin.expired-owners.index')->with([
      'message' => '期限切れオーナーを削除しました',
      'status' => 'alert'
    ]);
  }
}
