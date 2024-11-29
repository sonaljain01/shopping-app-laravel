<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function edit()
    {
        $shiprocket_email = env('SHIPROCKET_USERNAME');
        $shiprocket_password = env('SHIPROCKET_PASSWORD');
        $shiprocket_channel_id = env('SHIPROCKET_CHANNEL_ID');
        return view('admin.settings.edit', compact('shiprocket_email', 'shiprocket_password', 'shiprocket_channel_id'));
    }

    public function update()
    {
        $shiprocket_email = request('SHIPROCKET_USERNAME');
        $shiprocket_password = request('shiprocket_password');
        $shiprocket_channel_id = request('shiprocket_channel_id');
        if (env('SHIPROCKET_USERNAME') != $shiprocket_email || env('SHIPROCKET_PASSWORD') != $shiprocket_password || env('SHIPROCKET_CHANNEL_ID') != $shiprocket_channel_id) {
            $envFile = file_get_contents(base_path('.env'));
            $envFile = str_replace("SHIPROCKET_USERNAME=" . env('SHIPROCKET_USERNAME'), "SHIPROCKET_USERNAME=" . $shiprocket_email, $envFile);
            $envFile = str_replace("SHIPROCKET_PASSWORD=" . env('SHIPROCKET_PASSWORD'), "SHIPROCKET_PASSWORD=" . $shiprocket_password, $envFile);
            $envFile = str_replace("SHIPROCKET_CHANNEL_ID=" . env('SHIPROCKET_CHANNEL_ID'), "SHIPROCKET_CHANNEL_ID=" . $shiprocket_channel_id, $envFile);
            file_put_contents(base_path('.env'), $envFile);
        }

        return redirect()->route('settings.edit')->with('success', 'Settings updated successfully');
    }
}
