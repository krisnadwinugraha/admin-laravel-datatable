<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DB;
use Hash;
use Illuminate\Support\Arr;
use Yajra\Datatables\Datatables;
use RealRashid\SweetAlert\Facades\Alert;

class RoleController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:role-list|role-create|role-edit|role-delete', ['only' => ['index','store']]);
        $this->middleware('permission:role-create', ['only' => ['create','store']]);
        $this->middleware('permission:role-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:role-delete', ['only' => ['destroy']]);
    }
   
    public function index(Request $request)
    {
        $roles = Role::orderBy('id','DESC')->paginate(5);
        return view('role.index',compact('roles'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function create()
    {
        $permission = Permission::get();
        return view('role.create',compact('permission'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:roles,name',
            'permission' => 'required',
        ]);
    
        $role = Role::create(['name' => $request->input('name')]);
        $role->syncPermissions($request->input('permission'));

        Alert::success('Success', 'Role created successfully');
    
        return redirect()->route('role.index');
    }

    public function show($id)
    {
        $role = Role::find($id);
        $rolePermissions = Permission::join("role_has_permissions","role_has_permissions.permission_id","=","permissions.id")
            ->where("role_has_permissions.role_id",$id)
            ->get();
    
        return view('role.show',compact('role','rolePermissions'));
    }

    public function edit($id)
    {
        $role = Role::find($id);
        $permission = Permission::get();
        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id",$id)
            ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
            ->all();
    
        return view('role.edit',compact('role','permission','rolePermissions'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'permission' => 'required',
        ]);
    
        $role = Role::find($id);
        $role->name = $request->input('name');
        $role->save();
    
        $role->syncPermissions($request->input('permission'));

        Alert::success('Success', 'Role updated successfully');
    
        return redirect()->route('role.index');
    }

    public function destroy($id)
    {
        DB::table("roles")->where('id',$id)->delete();
        
        Alert::success('Success', 'Role deleted successfully');

        return redirect()->route('role.index');
    }

    public function roleData(Request $request)
    {
        if ($request->ajax()) {
            $data = Role::latest()->get();
            return Datatables::of($data)
            ->addColumn('action', function($row){
                $actionBtn = '
                <form action="'.route('role.destroy',$row->id).'" method="POST">
                <a href="role/'.$row->id.'/" class="btn btn-info btn-sm">Show</a>
                <a href="role/'.$row->id.'/edit" class="btn btn-success btn-sm">Edit</a>
                    '.csrf_field().'
                    '.method_field("DELETE").'
                    <button type="submit" class="btn btn-danger btn-sm"
                        onclick="return confirm(\'Are You Sure Want to Delete?\')"
                        >Delete</a>
                    </form>';
                return $actionBtn;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
    }
}
