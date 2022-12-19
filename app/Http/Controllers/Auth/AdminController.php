<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;
use Auth;

class AdminController extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }

    public function index() {
        return view('admin.dashboard');
    }

    public function showUsers() {
        $users = User::all();
        return view('admin.userList', compact('users'));
    }

    public function edit($id) {
        $user = User::findOrFail($id);
        return view('admin.userEdit', compact('user'));
    }

    /**
     * Update User.
     * 
     * @param  Request  $request
     * @return Response
     */
    public function update(Request $request, $id) {
        $user = User::findOrFail($id);
        //dd($id);
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|string|email|unique:users,email,' . $user->id,
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->password != NULL) {
            $user->password = Hash::make($request->password);
        }
        $user->save();
        return Redirect()->route('userList')->with('status', 'User Updated Successfully');
    }

    public function destroy($id) {
        $user = User::findOrFail($id);
        if (!is_null($user)) {
            $user->delete();
        }

        return Redirect()->route('userList')->with('status', 'User Deleted Successfully');
    }

    public function search(Request $request) {

        $name = $request->name;
        $gender = $request->gender;
        //$sd = toString();
        //dd($gender);
//        $users = User::orderBy('id', 'desc')
//                ->where('gender',$gender)
//                ->where(function($query){
//                    $query->where('first_name', 'like', '%'.$name.'%')
//                            ->orWhere('last_name', 'like', '%'.$name.'%');
//                }) 
//                ->get();

        $users = User::orderBy('id', 'desc');
        if (!empty($gender)) {
            $users = $users->where('gender', $gender);
        }
        if (!empty($name)) {
            $users = $users->where('first_name', 'like', '%' . $name . '%')
                    ->orWhere('last_name', 'like', '%' . $name . '%');
        }
        $users = $users->get();
        //echo '<pre>';print_r($users);exit;

        return view('admin.userSearch', compact('users', 'name', 'gender'));
    }
    
    
}
