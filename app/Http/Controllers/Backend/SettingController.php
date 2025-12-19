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
                'favicon' => null,
                'phone' => null,
                'address' => null,
                'email' => null,
                'facebook' => null,
                'twitter' => null,
                'copyright' => null,
                'primary_color' => '#B56952',
                'secondary_color' => '#C890FF',
                'accent_color' => '#EE786C',
                'text_color' => '#292323',
                'link_color' => '#B56952',
            ]);
        } else {
            // Set default colors if not set
            if (!$site->primary_color) $site->primary_color = '#B56952';
            if (!$site->secondary_color) $site->secondary_color = '#C890FF';
            if (!$site->accent_color) $site->accent_color = '#EE786C';
            if (!$site->text_color) $site->text_color = '#292323';
            if (!$site->link_color) $site->link_color = '#B56952';
            $site->save();
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
            'favicon' => 'nullable|image|mimes:png,ico|max:1024',
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

        if ($request->hasFile('favicon')) {
            // Delete old favicon
            if ($site->favicon && File::exists(public_path($site->favicon))) {
                File::delete(public_path($site->favicon));
            }

            $favicon = $request->file('favicon');
            $name_gen = hexdec(uniqid()) . '.' . $favicon->getClientOriginalExtension();
            $favicon->move(public_path('upload/favicon'), $name_gen);
            
            $site->favicon = 'upload/favicon/' . $name_gen;
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

    /**
     * Display color setting page (Backend)
     */
    public function ColorSetting()
    {
        $site = SiteSetting::first();
        
        // If no site setting exists, create a default one
        if (!$site) {
            $site = SiteSetting::create([
                'logo' => null,
                'favicon' => null,
                'phone' => null,
                'address' => null,
                'email' => null,
                'facebook' => null,
                'twitter' => null,
                'copyright' => null,
                'primary_color' => '#B56952',
                'secondary_color' => '#C890FF',
                'accent_color' => '#EE786C',
                'text_color' => '#292323',
                'link_color' => '#B56952',
            ]);
        } else {
            // Set default colors if not set
            if (!$site->primary_color) $site->primary_color = '#B56952';
            if (!$site->secondary_color) $site->secondary_color = '#C890FF';
            if (!$site->accent_color) $site->accent_color = '#EE786C';
            if (!$site->text_color) $site->text_color = '#292323';
            if (!$site->link_color) $site->link_color = '#B56952';
            $site->save();
        }
        
        return view('backend.setting.color_setting', compact('site'));
    }

    /**
     * Update color setting (Backend)
     */
    public function ColorUpdate(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:site_settings,id',
            'primary_color' => 'nullable|string|max:7|regex:/^#[a-fA-F0-9]{6}$/',
            'secondary_color' => 'nullable|string|max:7|regex:/^#[a-fA-F0-9]{6}$/',
            'accent_color' => 'nullable|string|max:7|regex:/^#[a-fA-F0-9]{6}$/',
            'text_color' => 'nullable|string|max:7|regex:/^#[a-fA-F0-9]{6}$/',
            'link_color' => 'nullable|string|max:7|regex:/^#[a-fA-F0-9]{6}$/',
        ]);

        $site = SiteSetting::findOrFail($request->id);

        $site->primary_color = $request->primary_color ?? '#B56952';
        $site->secondary_color = $request->secondary_color ?? '#C890FF';
        $site->accent_color = $request->accent_color ?? '#EE786C';
        $site->text_color = $request->text_color ?? '#292323';
        $site->link_color = $request->link_color ?? '#B56952';
        $site->save();

        $notification = array(
            'message' => 'Color Setting Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }
}