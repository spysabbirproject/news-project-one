<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\About_us;
use App\Models\Admin;
use App\Models\Category;
use App\Models\News;
use App\Models\Tag;
use App\Models\User;
use App\Models\Visitor_detail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;

class AdminController extends Controller
{
    public function dashboard()
    {
        $all_news = News::all();
        $categories = Category::all();
        $tags = Tag::all();
        $visitors = Visitor_detail::all();
        $all_administrator = Admin::all();
        $all_user = User::all();
        return view('admin.dashboard', compact('all_news', 'categories', 'tags', 'visitors', 'all_administrator', 'all_user'));
    }

    public function profile()
    {
        return view('admin.profile.index');
    }

    public function profileUpdate(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'profile_photo' => 'nullable|image|mimes:png,jpg',
            'phone_number' => 'nullable|digits:11',
        ]);

        Admin::find(Auth::guard('admin')->id())->update([
            'name' => $request->name,
            'gender' => $request->gender,
            'phone_number' => $request->phone_number,
            'date_of_birth' => $request->date_of_birth,
            'address' => $request->address,
        ]);

        if($request->hasFile('profile_photo')){
            if(Auth::guard('admin')->user()->profile_photo != "default_profile_photo.png"){
                unlink(base_path("public/uploads/profile_photo/").Auth::guard('admin')->user()->profile_photo);
            }
            $profile_photo_name =  "Admin-Profile-Photo-".Auth::guard('admin')->user()->id.".". $request->file('profile_photo')->getClientOriginalExtension();
            $upload_link = base_path("public/uploads/profile_photo/").$profile_photo_name;
            Image::make($request->file('profile_photo'))->resize(300,300)->save($upload_link);
            Admin::find(Auth::guard('admin')->id())->update([
                'profile_photo' => $profile_photo_name
            ]);
        }
        $notification = array(
            'message' => 'Profile change successfully.',
            'alert-type' => 'success'
        );

        return back()->with($notification);
    }

    public function passwordUpdate(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|confirmed|min:8',
            'password_confirmation' => 'required',
        ]);
        if($request->current_password == $request->password){
            return back()->withErrors(['password_error' => 'New password can not same as old password']);
        }
        if(Hash::check($request->current_password, Auth::guard('admin')->user()->password)){
            Admin::find(Auth::guard('admin')->id())->update([
                'password' => Hash::make($request->password)
            ]);
            $notification = array(
                'message' => 'Password change successfully.',
                'alert-type' => 'success'
            );

            return back()->with($notification);
        }else{
            return back()->withErrors(['password_error' => 'Your Old Password is Wrong!']);
        }
    }

    public function allAdministrator (Request $request)
    {
        if ($request->ajax()) {
            $all_administrator = "";
            $query = Admin::select('admins.*');

            if($request->status){
                $query->where('admins.status', $request->status);
            }
            if($request->role){
                $query->where('admins.role', $request->role);
            }

            $all_administrator = $query->get();

            return Datatables::of($all_administrator)
                    ->addIndexColumn()
                    ->editColumn('profile_photo', function($row){
                        return '<img src="'.asset('uploads/profile_photo').'/'.$row->profile_photo.'" width="40" >';
                    })
                    ->editColumn('status', function($row){
                        if($row->status == "Active"){
                            return'
                            <span class="badge bg-success">'.$row->status.'</span>
                            <button type="button" id="'.$row->id.'" class="btn btn-warning btn-sm statusBtn"><i class="fa-solid fa-ban"></i></button>
                            ';
                        }else{
                            return'
                            <span class="badge bg-warning">'.$row->status.'</span>
                            <button type="button" id="'.$row->id.'" class="btn btn-success btn-sm statusBtn"><i class="fa-solid fa-check"></i></button>
                            ';
                        }
                    })
                    ->addColumn('role', function($row){
                        if($row->role == "Admin"){
                            return'
                            <span class="badge bg-success">'.$row->role.'</span>
                            ';
                        }else{
                            return'
                            <span class="badge bg-warning">'.$row->role.'</span>
                            ';
                        }
                    })
                    ->addColumn('action', function($row){
                        $btn = '
                            <button type="button" id="'.$row->id.'" class="btn btn-primary btn-sm editBtn" data-bs-toggle="modal" data-bs-target="#editModal"><i class="fa-regular fa-pen-to-square"></i></button>
                        ';
                        return $btn;
                    })
                    ->rawColumns(['profile_photo', 'status', 'role', 'action'])
                    ->make(true);
        }
        return view('admin.administrator.index');
    }

    public function administratoreEdit($id)
    {
        $administratore = Admin::where('id', $id)->first();
        return response()->json($administratore);
    }

    public function administratoreUpdate(Request $request, $id)
    {
        $administratore = Admin::where('id', $id)->first();

        $validator = Validator::make($request->all(), [
            '*' => 'required',
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 400,
                'error'=> $validator->errors()->toArray()
            ]);
        }else{
            $administratore->update([
                'role' => $request->role,
                'updated_by' => Auth::guard('admin')->user()->id,
            ]);
            return response()->json([
                'status' => 200,
                'message' => 'Administrator update successfully',
            ]);
        }
    }

    public function administratorStatus($id)
    {
        $administrator = Admin::where('id', $id)->first();
        if($administrator->status == "Active"){
            $administrator->update([
                'status' => "Inactive",
                'updated_by' => Auth::guard('admin')->user()->id,
            ]);
            return response()->json([
                'message' => 'Administrator status inactive',
            ]);
        }else{
            $administrator->update([
                'status' =>"Active",
                'updated_by' => Auth::guard('admin')->user()->id,
            ]);
            return response()->json([
                'message' => 'Administrator status active',
            ]);
        }
    }

    public function aboutUs (){
        $about_us = About_us::first();
        return view('admin.about_us.index', compact('about_us'));
    }

    public function aboutUsUpdate (Request $request, $id){
        $request->validate([
            '*' => 'required',
        ]);

        About_us::where('id', $id)->update([
            'short_description' => $request->short_description,
            'long_description' => $request->long_description,
            'our_vision' => $request->our_vision,
            'our_mission' => $request->our_mission,
            'updated_by' => Auth::guard('admin')->user()->id,
            'updated_at' => Carbon::now(),
        ]);

        $notification = array(
            'message' => 'About us details updated successfully.',
            'alert-type' => 'success'
        );

        return back()->with($notification);
    }

    public function allUser (Request $request)
    {
        if ($request->ajax()) {
            $all_user = "";
            $query = User::select('users.*');

            if($request->status){
                $query->where('users.status', $request->status);
            }

            $all_user = $query->get();

            return Datatables::of($all_user)
                    ->addIndexColumn()
                    ->editColumn('profile_photo', function($row){
                        return '<img src="'.asset('uploads/profile_photo').'/'.$row->profile_photo.'" width="40" >';
                    })
                    ->editColumn('last_active', function($row){
                        return'
                        <span class="badge bg-info">'.date('d-M,Y h:m:s A', strtotime($row->last_active)).'</span>
                        ';
                    })
                    ->editColumn('created_at', function($row){
                            return'
                            <span class="badge bg-success">'.$row->created_at->format('d-M,Y h:m:s A').'</span>
                            ';
                    })
                    ->editColumn('status', function($row){
                        if($row->status == "Active"){
                            return'
                            <span class="badge bg-success">'.$row->status.'</span>
                            <button type="button" id="'.$row->id.'" class="btn btn-warning btn-sm statusBtn"><i class="fa-solid fa-ban"></i></button>
                            ';
                        }else{
                            return'
                            <span class="badge bg-warning">'.$row->status.'</span>
                            <button type="button" id="'.$row->id.'" class="btn btn-success btn-sm statusBtn"><i class="fa-solid fa-check"></i></button>
                            ';
                        }
                    })
                    ->rawColumns(['profile_photo', 'last_active', 'created_at', 'status'])
                    ->make(true);
        }
        return view('admin.user.index');
    }

    public function userStatus($id)
    {
        $user = User::where('id', $id)->first();
        if($user->status == "Active"){
            $user->update([
                'status' => "Inactive",
                'updated_by' => Auth::guard('admin')->user()->id,
            ]);
            return response()->json([
                'message' => 'User status active.'
            ]);
        }else{
            $user->update([
                'status' =>"Active",
                'updated_by' => Auth::guard('admin')->user()->id,
            ]);
            return response()->json([
                'message' => 'User status active.'
            ]);
        }
    }

}
