<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Redirect,Response;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\Datatables\Datatables;
use DB;
use Illuminate\Support\Arr;

class CategoryController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:category-list|category-create|category-edit|category-delete', ['only' => ['index','store']]);
        $this->middleware('permission:category-create', ['only' => ['create','store']]);
        $this->middleware('permission:category-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:category-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $data['categories'] = Category::orderBy('id','desc')->paginate(8);
        return view('categories', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required|max:255',
        ]);
 
        $category = Category::updateOrCreate(['id' => $request->id], [
            'name' => $request->name,
        ]);
 
        return response()->json(['code'=>200, 'message'=>'Category Created successfully','data' => $category], 200);
    }

    public function show($id)
    {
        return response()->json(Category::find($id));
    }

    public function edit($id)
    {
        return Response::json(Category::find($id));
    }

    public function destroy($id)
    {
        return Response::json(Category::find($id)->delete());
    }

    public function categoryData(Request $request)
    {
        if ($request->ajax()) {
            $data = Category::latest()->get();
            return Datatables::of($data)
            ->addColumn('action', function($row){
                $actionBtn = '
                <a href="javascript:void(0)" data-id="{{ '.$row->id.' }}" onclick="editCategory('.$row->id.')" class="btn btn-success">Edit</a>
                <a href="javascript:void(0)" data-id="{{ '.$row->id.' }}" class="btn btn-danger" onclick="deleteCategory('.$row->id.')">Delete</a>';
                return $actionBtn;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
    }
}
