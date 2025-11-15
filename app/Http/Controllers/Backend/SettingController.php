<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Models\SiteSetting;
use App\Models\SmtpSetting;
use Illuminate\Support\Facades\File;

class SettingController extends Controller
{
    /**
     * Display site setting page (Backend)
     */
    public function SiteSetting()
    {
        $site = SiteSetting::first();
        
        // If no site setting exists, create a default one
        if (!$site) {
            $site = SiteSetting::create([
                'logo' => null,
                'phone' => null,
                'address' => null,
                'email' => null,
                'facebook' => null,
                'twitter' => null,
                'copyright' => null,
            ]);
        }
        
        return view('backend.site.site_update', compact('site'));
    }

    /**
     * Update site setting (Backend)
     */
    public function SiteUpdate(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:site_settings,id',
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'email' => 'nullable|email|max:255',
            'facebook' => 'nullable|string|max:255',
            'twitter' => 'nullable|string|max:255',
            'copyright' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $site = SiteSetting::findOrFail($request->id);

        if ($request->hasFile('logo')) {
            // Delete old logo
            if ($site->logo && File::exists(public_path($site->logo))) {
                File::delete(public_path($site->logo));
            }

            $image = $request->file('logo');
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('upload/logo'), $name_gen);
            
            $site->logo = 'upload/logo/' . $name_gen;
        }

        $site->phone = $request->phone;
        $site->address = $request->address;
        $site->email = $request->email;
        $site->facebook = $request->facebook;
        $site->twitter = $request->twitter;
        $site->copyright = $request->copyright;
        $site->save();

        $notification = array(
            'message' => 'Site Setting Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    /**
     * Display SMTP setting page (Backend)
     */
    public function SmtpSetting()
    {
        $smtp = SmtpSetting::first();
        
        // If no SMTP setting exists, create a default one
        if (!$smtp) {
            $smtp = SmtpSetting::create([
                'mailer' => 'smtp',
                'host' => null,
                'port' => null,
                'username' => null,
                'password' => null,
                'encryption' => null,
                'from_address' => null,
            ]);
        }
        
        return view('backend.setting.smpt_update', compact('smtp'));
    }

    /**
     * Update SMTP setting (Backend)
     */
    public function SmtpUpdate(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:smtp_settings,id',
            'mailer' => 'nullable|string|max:255',
            'host' => 'nullable|string|max:255',
            'port' => 'nullable|string|max:255',
            'username' => 'nullable|string|max:255',
            'password' => 'nullable|string|max:255',
            'encryption' => 'nullable|string|max:255',
            'from_address' => 'nullable|email|max:255',
        ]);

        $smtp = SmtpSetting::findOrFail($request->id);

        $smtp->mailer = $request->mailer;
        $smtp->host = $request->host;
        $smtp->port = $request->port;
        $smtp->username = $request->username;
        $smtp->password = $request->password;
        $smtp->encryption = $request->encryption;
        $smtp->from_address = $request->from_address;
        $smtp->save();

        $notification = array(
            'message' => 'SMTP Setting Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }
}