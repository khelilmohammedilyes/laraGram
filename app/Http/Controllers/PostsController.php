<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class PostsController extends Controller
{
    public function index(){
        //$users=auth()->user()->following->pluck('profiles.user_id');
        //$posts=Post::whereIn('user_id',$users)->latest()->paginate(5);
        $posts=Post::all();
        return view('posts.index',compact('posts'));
    }

    //authentication requirements
    public function __construct()
    {
        $this->middleware('auth');
    }

    //create posts
    public function create(){
        return view('posts.create');
    }

    //store posts
    public function store(Request $request){
        $validation=$request->validate([
            'caption'=>'required',
            'image'=>['required','image']
        ]);

        $imagePath=request('image')->store('uploads','public');
        $image=Image::make(public_path("storage/{$imagePath}"))->fit(1200,1200);
        $image->save();
        $validation['image']=$imagePath;
        $validation['user_id']=auth()->id();
        Post::create($validation);

        return redirect('/profile/'.auth()->user()->id);
    }

    //show one post
    public function show(Post $post){
        return view('posts.show',compact('post'));
    }
}
