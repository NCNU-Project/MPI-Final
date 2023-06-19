<?php

namespace App\Http\Controllers;

use App\Models\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Str;
// use App\Models\User;

class UploadsController extends Controller
{

    public function index(Request $request)
    {
        $uploads = Upload::get();
        return view('dashboard', [
            'uploads' => $uploads,
        ]);
    }

    /**
     * Create the User's upload information.
     */
    public function create(Request $request)
    {
        $this->validate($request, [
            // 'video' => ['required', 'mimes:jpg,jpeg,png,gif']
            'video' => ['required', 'mimetypes:video/mp4']
        ]);

        $upload_path = null;
        if($request->hasFile('video')){
            $upload_path = $request->File('video')->storeAs(
                'videos', 
                Str::uuid() . '.' . $request->file('video')->getClientOriginalExtension(),
                'public'
            );
        }

        $request->user()->uploads()->create([
            'status' => 'undone',
            'upload_path' => $upload_path,
        ]);

        // $request->user()->fill($user);
        // $request->user()->save();

        return back();
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request)
    {
        dd(2);
        return 'hello world';
    }
}
