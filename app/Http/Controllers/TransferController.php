<?php

namespace App\Http\Controllers;

use App\Models\Transfer;
use Illuminate\Http\Request;
use Redirect,Response;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\Datatables\Datatables;
use DB;
use App\Exports\TransferExport;
use Excel;
use PDF;
use Illuminate\Support\Arr;

class TransferController extends Controller
{
    public function index()
    {
        $data['transfer'] = Transfer::orderBy('id','desc')->paginate(8);
        return view('transfer', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required|max:255',
        ]);
 
        $transfer = Transfer::updateOrCreate(['id' => $request->id], [
            'name' => $request->name,
        ]);
 
        return response()->json(['code'=>200, 'message'=>'Transfer Created successfully','data' => $transfer], 200);
    }

    public function show($id)
    {
        return response()->json(Transfer::find($id));
    }

    public function edit($id)
    {
        return Response::json(Transfer::find($id));
    }

    public function destroy($id)
    {
        return Response::json(Transfer::find($id)->delete());
    }

    public function transferData(Request $request)
    {
        if ($request->ajax()) {
            $data = Transfer::latest()->get();
            return Datatables::of($data)
            ->addColumn('action', function($row){
                $actionBtn = '
                <a href="javascript:void(0)" data-id="{{ '.$row->id.' }}" onclick="editTransfer('.$row->id.')" class="btn btn-success">Edit</a>
                <a href="javascript:void(0)" data-id="{{ '.$row->id.' }}" class="btn btn-danger" onclick="deleteTransfer('.$row->id.')">Delete</a>';
                return $actionBtn;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
    }

    public function export()
	{
		return Excel::download(new TransferExport, 'transfer.xlsx');
	}

    public function import(Request $request){
        Excel::import(new TransferImport, 
        $request->file('file')->store('files'));
    }

    public function pdf()
    {
    	$transfer = Transfer::all();
 
	    $pdf = PDF::loadview('transferTable',['transfer'=>$transfer]);
	    return $pdf->stream();
    }
}
