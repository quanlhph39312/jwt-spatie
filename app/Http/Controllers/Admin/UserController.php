<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:create-users|view-users|update-users|delete-users', ['only' => 'index']);
        $this->middleware('permission:create-users', ['only' => ['store']]);
        $this->middleware('permission:update-users', ['only' => ['show', 'update']]);
        $this->middleware('permission:delete-users', ['only' => ['destroy']]);
    }

    public function index()
    {

        return response()->json(User::all(), 200);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);
        return response()->json($user, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::findOrFail($id);
        return response()->json($user, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);
        $data = $request->all();
        $update = $user->update($data);
        response()->json($update, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        response()->json(['message' => 'Xóa thành công']);
    }
}
