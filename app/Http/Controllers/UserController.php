<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Redirect,Response;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\Datatables\Datatables;
use Spatie\Permission\Models\Role;
use DB;
use Illuminate\Support\Arr;

class UserController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:users-list|users-create|users-edit|users-delete', ['only' => ['index','store']]);
        $this->middleware('permission:users-create', ['only' => ['create','store']]);
        $this->middleware('permission:users-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:users-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $data = User::orderBy('id','DESC')->paginate(5);
        $roles = Role::pluck('name','name')->all();
        return view('users.index',compact('data','roles'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'phone'      => 'required',
            'alamat'      => 'required',
        ]);
                 
        $user = User::updateOrCreate(['id' => $request->id], [ 
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'alamat' => $request->alamat
        ]);

        $user->roles()->detach();
        $user->assignRole($request->input('role'));
              
        return response()->json(['code'=>200, 'message'=>'User Created successfully','data' => $user], 200);                  
    }

    public function show($id)
    {
        return response()->json(User::find($id));
    }

    public function edit($id)
    {   
        $user = User::find($id);
        $roles = Role::pluck('name','name')->all();
        $userRole = $user->roles->pluck('name','name')->all();
        return Response::json($user);
    }

    public function destroy($id)
    {
        return Response::json(User::find($id)->delete());
    }

    public function userData(Request $request)
    {
        if ($request->ajax()) {
            $data = User::latest()->with('roles')->get();
            return Datatables::of($data)
            ->addColumn('action', function($row){
                $actionBtn = '
                <a href="javascript:void(0)" data-id="{{ '.$row->id.' }}" onclick="editUser('.$row->id.')" class="btn btn-success">Edit</a>
                <a href="javascript:void(0)" data-id="{{ '.$row->id.' }}" class="btn btn-danger" onclick="deleteUser('.$row->id.')">Delete</a>';
                return $actionBtn;
            })
            ->addColumn('status', function($row){
                
                  $tag = "
                     <label class='badge badge-success'> {$row->getRoleNames()->implode('')} </label>
                     ";
                  return $tag;
                
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
        }
    }
}
