<?php

namespace App\Http\Controllers;

use App\Models\Upload;
use App\Models\FileStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Str;
use GuzzleHttp\Client;

// use App\Models\User;

class UploadsController extends Controller
{
    public function index(Request $request)
    {
        // $collection = collect(['account_id' => 1, 'product' => null, 'amount' => 5]);
        // dd($request->user()->pendingUploads());

        // dd($request->user()->uploads());
        $uploads = $request->user()->uploads;
        return view('dashboard', [
            'uploads' => $uploads,
        ]);
    }

    /**
     * Create the User's upload information.
     */
    public function create(Request $request)
    {
        $client = new \GuzzleHttp\Client();
        $this->validate($request, [
            // 'video' => ['required', 'mimes:jpg,jpeg,png,gif']
            'video' => ['required', 'mimetypes:video/mp4']
        ]);

        $upload_path = null;
        if ($request->hasFile('video')) {
            $upload_path = $request->File('video')->storeAs(
                'videos',
                Str::uuid() . '.' . $request->file('video')->getClientOriginalExtension(),
                'public'
            );
        }

        // create a docker container
        $res = $client->request('POST', 'http://v1.43/containers/create', [
            'json' => [
                "Image" => "test",
                "Cmd" => ["-f", $upload_path],
            ],
            'curl' => [
                CURLOPT_UNIX_SOCKET_PATH => '/var/run/docker.sock'
            ]
        ]);
        // dd(json_decode($res->getBody()->getContents()));

        $upload = $request->user()->uploads()->create([
            'upload_path' => $upload_path,
            'filename' => $request->File('video')->getClientOriginalName(),
            "ct_digest" => json_decode($res->getBody()->getContents())->Id,
        ]);

        // start execution
        $upload->file_status()->associate(FileStatus::find(2));
        $upload->save();
        // dd($upload->file_status->status);

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

    public function test(Request $request)
    {
        $uploads = array_values($request->user()->pendingUploads()->map(function ($item) {
            return $item->ct_digest;
        })->toArray());

        $filters = [
            "status" => ["exited"],
            "id" => $uploads
        ];

        $client = new \GuzzleHttp\Client();
        // get exited containers
        $res = $client->request('GET', 'http://v1.43/containers/json', [
            'query' => [
                'all' => 'true',
                'filters' => json_encode($filters),
            ],
            'curl' => [
                CURLOPT_UNIX_SOCKET_PATH => '/var/run/docker.sock'
            ]
        ]);
        $raw_status = json_decode($res->getBody()->getContents());

        // check run correctly
        $exit_normally_ct_status = array_filter($raw_status, function ($item) {
            return str_contains($item->Status, 'Exited (0)');
        });
        $exit_abnormally_ct_status = array_filter($raw_status, function ($item) {
            return !str_contains($item->Status, 'Exited (0)');
        });

        $exit_normally_ct_id = array_map(function ($item) {
            return $item->Id;
        }, $exit_normally_ct_status);
        $exit_abnormally_ct_id = array_map(function ($item) {
            return $item->Id;
        }, $exit_abnormally_ct_status);

        // dd($exit_ct_ids);
        // Upload::whereIn('ct_digest', $exit_ct_ids)->get()->dd();
        Upload::whereIn('ct_digest', $exit_normally_ct_id)->update(['file_status_id' => 3]);
        Upload::whereIn('ct_digest', $exit_abnormally_ct_id)->update(['file_status_id' => 4]);
        // Upload::where('ct_digest', $exit_ct_ids)->file_status()->associate(FileStatus::find(3));


        // dd($uploads->merge(collect(json_decode($res->getBody()->getContents()))));
        // return back();
        return 'test';
    }
}
