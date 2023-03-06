<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Country;
use App\Models\District;
use App\Models\Division;
use App\Models\News;
use App\Models\Tag;
use App\Models\Union;
use App\Models\Upazila;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $all_news = "";
            if(Auth::guard('admin')->user()->role == "Super Admin"  || Auth::guard('admin')->user()->role == "Admin"){
                $query = News::leftJoin('admins', 'news.created_by', 'admins.id');
            }else{
                $query = News::where('created_by', Auth::guard('admin')->user()->id)
                    ->leftJoin('admins', 'news.created_by', 'admins.id');
            }

            if($request->status){
                $query->where('news.status', $request->status);
            }

            if($request->news_position){
                $query->where('news.news_position', $request->news_position);
            }

            if($request->created_at){
                $query->where('news.created_at', 'LIKE', '%'.$request->created_at.'%');
            }

            $all_news = $query->select('news.*', 'admins.name',)->get();

            return Datatables::of($all_news)
                    ->addIndexColumn()
                    ->editColumn('news_thumbnail_photo', function($row){
                        return '<img src="'.asset('uploads/news_thumbnail_photo').'/'.$row->news_thumbnail_photo.'" width="40" >';
                    })
                    ->editColumn('name', function($row){
                        if(!$row->updated_by){
                            return'
                            <span class="badge bg-success">'.$row->name.'</span>
                            ';
                        }else{
                            return'
                            <span class="badge bg-warning">'.$row->name.'</span>
                            ';
                        }
                    })
                    ->editColumn('comment_count', function($row){
                        return'
                            <span class="badge bg-info">'.Comment::where('news_id', $row->id)->count().'</span>
                        ';
                    })
                    ->editColumn('created_at', function($row){
                        if(!$row->updated_at){
                            return'
                            <span class="badge bg-success">'.$row->created_at->format('d-M-Y h:m:s A').'</span>
                            ';
                        }else{
                            return'
                            <span class="badge bg-warning">'.$row->updated_at->format('d-M-Y h:m:s A').'</span>
                            ';
                        }
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
                    ->addColumn('action', function($row){
                        $btn = '
                            <a href="'.route('admin.news.edit', $row->id).'" class="btn btn-primary btn-sm"><i class="fa-regular fa-pen-to-square"></i></a>
                            <a href="'.route('admin.news.show', $row->id).'" class="btn btn-info btn-sm"><i class="fa-solid fa-eye"></i></a>
                            <button type="button" id="'.$row->id.'" class="btn btn-danger btn-sm deleteBtn"><i class="fa-solid fa-trash-can"></i></button>
                            ';
                        return $btn;
                    })
                    ->rawColumns(['news_thumbnail_photo', 'tag_name', 'name', 'comment_count', 'created_at', 'status', 'action'])
                    ->make(true);
        }

        return view('admin.news.index');
    }

    public function create()
    {
        $categories = Category::all();
        $tags = Tag::all();
        $countries = Country::all();
        return view('admin.news.create', compact('categories', 'tags', 'countries'));
    }

    public function getDivisions(Request $request){
        $send_data = "<option value='0'>--Select Division--</option>";
        $divisions = Division::where('country_id', $request->country_id)->get();
        foreach ($divisions as $division) {
            $send_data .= "<option value='$division->id' >$division->name</option>";
        }
        return response()->json($send_data);
    }

    public function getDistricts(Request $request){
        $send_data = "<option value='0'>--Select District--</option>";
        $districts = District::where('division_id', $request->division_id)->get();
        foreach ($districts as $district) {
            $send_data .= "<option value='$district->id' >$district->name</option>";
        }
        return response()->json($send_data);
    }

    public function getUpazilas(Request $request){
        $send_data = "<option value='0'>--Select Upazila--</option>";
        $upazilas = Upazila::where('district_id', $request->district_id)->get();
        foreach ($upazilas as $upazila) {
            $send_data .= "<option value='$upazila->id' >$upazila->name</option>";
        }
        return response()->json($send_data);
    }

    public function getUnions(Request $request){
        $send_data = "<option value='0'>--Select Union--</option>";
        $unions = Union::where('upazila_id', $request->upazila_id)->get();
        foreach ($unions as $union) {
            $send_data .= "<option value='$union->id' >$union->name</option>";
        }
        return response()->json($send_data);
    }

    public function store(Request $request)
    {
        $request->validate([
            '*' => 'required',
            'breaking_news' => 'nullable',
            'news_video_link' => 'nullable',
            'tags' => ['required', 'array', 'min:1'],
            'tags.*' => ['required', 'integer', 'exists:tags,id'],
            'news_thumbnail_photo' => 'nullable|image|mimes:png,jpg,jpeg,webp,svg',
            'news_cover_photo' => 'nullable|image|mimes:png,jpg,jpeg,webp,svg',
        ]);

        if($request->breaking_news == NULL){
            $breaking_news = "No";
        }else{
            $breaking_news = $request->breaking_news;
        }

        $news_slug = Str::slug($request->news_headline);

        // News Thumbnail Photo Upload
        if($request->news_position == 'Top Slider'){
            $size  = "800, 500";
        }else{
            $size  = "700, 435";
        }
        if($request->hasFile('news_thumbnail_photo')){
            $news_thumbnail_photo_name =  "News-Thumbnail-Photo-".Str::random(5).".". $request->file('news_thumbnail_photo')->getClientOriginalExtension();
            $upload_link = base_path("public/uploads/news_thumbnail_photo/").$news_thumbnail_photo_name;
            Image::make($request->file('news_thumbnail_photo'))->resize($size)->save($upload_link);
        }else{
            $news_thumbnail_photo_name= 'default_news_thumbnail_photo.jpg';
        }

        // News Cover Photo Upload
        if($request->hasFile('news_cover_photo')){
            $news_cover_photo_name =  "News-Cover-Photo-".Str::random(5).".". $request->file('news_cover_photo')->getClientOriginalExtension();
            $upload_link = base_path("public/uploads/news_cover_photo/").$news_cover_photo_name;
            Image::make($request->file('news_cover_photo'))->resize(850, 450)->save($upload_link);
        }else{
            $news_cover_photo_name= 'default_news_cover_photo.jpg';
        }

        $news = new News();
        $news->news_position = $request->news_position;
        $news->breaking_news = $breaking_news;
        $news->news_headline = $request->news_headline;
        $news->news_slug = $news_slug;
        $news->country_id = $request->country_id;
        $news->division_id = $request->division_id;
        $news->district_id = $request->district_id;
        $news->upazila_id = $request->upazila_id;
        $news->union_id = $request->union_id;
        $news->news_category_id = $request->news_category_id;
        $news->news_quote = $request->news_quote;
        $news->news_details = $request->news_details;
        $news->news_video_link = $request->news_video_link;
        $news->news_thumbnail_photo = $news_thumbnail_photo_name;
        $news->news_cover_photo = $news_cover_photo_name;
        $news->created_by = Auth::guard('admin')->user()->id;
        $news->created_at = Carbon::now();
        $news->save();

        $news->tags()->attach($request->tags);

        $notification = array(
            'message' => 'News create successfully.',
            'alert-type' => 'success'
        );

        return redirect()->route('admin.news.index')->with($notification);
    }

    public function show($id)
    {
        $news = News::where('id', $id)->first();
        return view('admin.news.show', compact('news'));
    }

    public function edit($id)
    {
        $categories = Category::all();
        $tags = Tag::all();
        $countries = Country::all();
        $news = News::where('id', $id)->first();
        return view('admin.news.edit', compact('categories', 'tags', 'countries', 'news'));
    }

    public function update(Request $request, $id)
    {
        $news = News::where('id', $id)->first();

        $news_slug = Str::slug($request->news_headline);

        $request->validate([
            'news_position' => 'required',
            'news_headline' => 'required|unique:news,news_headline,'. $news->id,
            'news_category_id' => 'required',
            'news_quote' => 'required',
            'news_details' => 'required',
            'tags' => ['required', 'array', 'min:1'],
            'tags.*' => ['required', 'integer', 'exists:tags,id'],
            'news_thumbnail_photo' => 'nullable|image|mimes:png,jpg,jpeg,webp,svg',
            'news_cover_photo' => 'nullable|image|mimes:png,jpg,jpeg,webp,svg',
        ]);

        if($request->breaking_news == NULL){
            $breaking_news = "No";
        }else{
            $breaking_news = $request->breaking_news;
        }

        $news->update([
            'news_position' => $request->news_position,
            'breaking_news' => $breaking_news,
            'news_headline' => $request->news_headline,
            'news_slug' => $news_slug,
            'country_id' => $request->country_id,
            'division_id' => $request->division_id,
            'district_id' => $request->district_id,
            'upazila_id' => $request->upazila_id,
            'union_id' => $request->union_id,
            'news_category_id' => $request->news_category_id,
            'news_quote' => $request->news_quote,
            'news_details' => $request->news_details,
            'news_video_link' => $request->news_video_link,
            'updated_by' => Auth::guard('admin')->user()->id,
        ]);

        $news->tags()->sync($request->tags);

        // News Thumbnail Photo Upload
        if($request->news_position == 'Top Slider'){
            $size  = "800, 500";
        }else{
            $size  = "700, 435";
        }
        if($request->hasFile('news_thumbnail_photo')){
            if($news->news_thumbnail_photo != 'default_news_thumbnail_photo.jpg'){
                unlink(base_path("public/uploads/news_thumbnail_photo/").$news->news_thumbnail_photo);
            }
            $news_thumbnail_photo_name =  "News-Thumbnail-Photo-".Str::random(5).".". $request->file('news_thumbnail_photo')->getClientOriginalExtension();
            $upload_link = base_path("public/uploads/news_thumbnail_photo/").$news_thumbnail_photo_name;
            Image::make($request->file('news_thumbnail_photo'))->resize($size)->save($upload_link);
            $news->update([
                'news_thumbnail_photo' => $news_thumbnail_photo_name,
                'updated_by' => Auth::guard('admin')->user()->id,
            ]);
        }

        // News Cover Photo Upload
        if($request->hasFile('news_cover_photo')){
            if($news->news_cover_photo != 'default_news_cover_photo.jpg'){
                unlink(base_path("public/uploads/news_cover_photo/").$news->news_cover_photo);
            }
            $news_cover_photo_name =  "News-Cover-Photo-".Str::random(5).".". $request->file('news_cover_photo')->getClientOriginalExtension();
            $upload_link = base_path("public/uploads/news_cover_photo/").$news_cover_photo_name;
            Image::make($request->file('news_cover_photo'))->resize(850, 450)->save($upload_link);
            $news->update([
                'news_cover_photo' => $news_cover_photo_name,
                'updated_by' => Auth::guard('admin')->user()->id,
            ]);
        }
        $notification = array(
            'message' => 'News create successfully.',
            'alert-type' => 'success'
        );

        return redirect()->route('admin.news.index')->with($notification);
    }

    public function destroy($id)
    {
        $news = News::where('id', $id)->first();
        $news->updated_by = Auth::guard('admin')->user()->id;
        $news->deleted_by = Auth::guard('admin')->user()->id;
        $news->save();
        $news->delete();
        return response()->json([
            'message' => 'News destroy successfully.'
        ]);
    }

    public function trashed(Request $request)
    {
        if ($request->ajax()) {
            $trashed_news = "";

            if(Auth::guard('admin')->user()->role == "Admin"){
                $query = News::onlyTrashed();
            }else{
                $query = News::where('created_by', Auth::guard('admin')->user()->id)
                    ->onlyTrashed();
            }

            $trashed_news = $query->get();

            return Datatables::of($trashed_news)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    if(Auth::guard()->user()->role == 'Super Admin'){
                        $btn = '
                        <button type="button" id="'.$row->id.'" class="btn btn-danger btn-sm restoreBtn"><i class="fa-solid fa-rotate"></i></button>
                        <button type="button" id="'.$row->id.'" class="btn btn-danger btn-sm forceDeleteBtn"><i class="fa-solid fa-delete-left"></i></button>
                        ';
                        return $btn;
                    }else{
                        $btn = '
                        <button type="button" id="'.$row->id.'" class="btn btn-danger btn-sm restoreBtn"><i class="fa-solid fa-rotate"></i></button>
                        ';
                        return $btn;
                    }
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function restore($id)
    {
        News::onlyTrashed()->where('id', $id)->update([
            'deleted_by' => NULL
        ]);
        News::onlyTrashed()->where('id', $id)->restore();
        return response()->json([
            'message' => 'News restore successfully.'
        ]);
    }

    public function forceDelete($id)
    {
        $news = News::onlyTrashed()->where('id', $id)->first();
        if ($news->news_thumbnail_photo != "default_news_thumbnail_photo.jpg") {
            unlink(base_path("public/uploads/news_thumbnail_photo/").$news->news_thumbnail_photo);
        }
        if ($news->news_cover_photo != "default_news_cover_photo.jpg") {
            unlink(base_path("public/uploads/news_cover_photo/").$news->news_cover_photo);
        }
        News::onlyTrashed()->where('id', $id)->forceDelete();
        return response()->json([
            'message' => 'News force delete successfully.'
        ]);
    }

    public function status($id)
    {
        $news = News::where('id', $id)->first();
        if($news->status == "Active"){
            $news->update([
                'status' => "Inactive",
                'updated_by' => Auth::guard('admin')->user()->id,
            ]);
            return response()->json([
                'message' => 'News status inactive.'
            ]);
        }else{
            $news->update([
                'status' =>"Active",
                'updated_by' => Auth::guard('admin')->user()->id,
            ]);
            return response()->json([
                'message' => 'News status active.'
            ]);
        }
    }
}
