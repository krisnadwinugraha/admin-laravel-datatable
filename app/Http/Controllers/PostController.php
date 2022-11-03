<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;
use Redirect,Response;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\Datatables\Datatables;
use DB;
use Illuminate\Support\Arr;


class PostController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:posts-list|posts-create|posts-edit|posts-delete', ['only' => ['index','store']]);
         $this->middleware('permission:posts-create', ['only' => ['create','store']]);
         $this->middleware('permission:posts-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:posts-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['posts'] = Post::orderBy('id','desc')->paginate(8);
        $data['category'] = Category::pluck('name','id')->all();

        return view('posts', $data);
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
            'title'       => 'required|max:255',
            'description' => 'required',
            'category_id' => 'required',
            'image' => 'nullable',
        ]);

        $post = Post::updateOrCreate(['id' => $request->id], [
            'title' => $request->title,
            'description' => $request->description,
            'category_id' => $request->category_id
        ]);

        if($request->file('image')){
            \File::delete(public_path('img/post').$request->hidden_image);
            $file= $request->file('image');
            $filename= date('YmdHi').$file->getClientOriginalName();
            $file-> move(public_path('img/post'), $filename);
            $post['image']= $filename;
        }

        $post->save();

        return response()->json(['code'=>200, 'message'=>'Post Created successfully','data' => $post], 200);

     }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post = Post::find($id);

        return Response::json($post);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::find($id);

        return response()->json($post);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post= Post::find($id)->delete();
        return Response::json($post);
    }

    public function postData(Request $request)
    {
        if ($request->ajax()) {
            $data = Post::latest()->get();
            return Datatables::of($data)
            ->addColumn('action', function($row){
                $actionBtn = '
                <a href="javascript:void(0)" data-id="{{ '.$row->id.' }}" onclick="editPost('.$row->id.')" class="btn btn-success">Edit</a>
                <a href="javascript:void(0)" data-id="{{ '.$row->id.' }}" class="btn btn-danger" onclick="deletePost('.$row->id.')">Delete</a>';
                return $actionBtn;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
    }
}