<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page_setting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class Page_settingController extends Controller
{
    public function index(){
        $all_pags = Page_setting::all();
        return view('admin.setting.page', compact('all_pags'));
    }

    public function store(Request $request){

        $validator = Validator::make($request->all(), [
            '*' => 'required',
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 400,
                'error'=> $validator->errors()->toArray()
            ]);
        }else{
            $page_slug = Str::slug($request->page_name);

            Page_setting::insert([
                'page_name' => $request->page_name,
                'page_slug' => $page_slug,
                'page_description' => $request->page_description,
                'created_by' => Auth::guard('admin')->user()->id,
                'created_at' => Carbon::now(),
            ]);
            return response()->json([
                'status' => 200,
                'message' => 'Page create successfully.'
            ]);
        }
    }

    public function edit($id)
    {
        $page_setting = Page_setting::where('id', $id)->first();
        return response()->json($page_setting);
    }

    public function update(Request $request, $id){
        $page_setting = Page_setting::where('id', $id)->first();

        $validator = Validator::make($request->all(), [
            '*' => 'required',
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 400,
                'error'=> $validator->errors()->toArray()
            ]);
        }else{
            $page_slug = Str::slug($request->page_name);

            $page_setting->update([
                'page_name' => $request->page_name,
                'page_slug' => $page_slug,
                'page_description' => $request->page_description,
                'updated_by' => Auth::guard('admin')->user()->id,
            ]);
            return response()->json([
                'status' => 200,
                'message' => 'Page update successfully.'
            ]);
        }
    }

    public function destroy($id)
    {
        Page_setting::where('id', $id)->delete();
        return response()->json([
            'message' => 'Page destroy successfully.'
        ]);
    }

    public function status($id)
    {
        $page_setting = Page_setting::where('id', $id)->first();
        if($page_setting->status == "Active"){
            $page_setting->update([
                'status' => "Inactive",
                'updated_by' => Auth::guard('admin')->user()->id,
            ]);
        }else{
            $page_setting->update([
                'status' =>"Active",
                'updated_by' => Auth::guard('admin')->user()->id,
            ]);
        }

        $notification = array(
            'message' => 'Page status updated successfully.',
            'alert-type' => 'success'
        );

        return back()->with($notification);
    }
}
