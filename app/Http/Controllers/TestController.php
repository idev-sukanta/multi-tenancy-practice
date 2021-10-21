<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Config;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TestController extends Controller
{
    public function testDb(Request $request)
    {
        Artisan::call('optimize:clear');
    }

    public function createSchool(Request $request)
    {
        $request->validate([
            'school_name' => 'required|string',
            'domain' => 'required|string',
            'name' => 'required',
            'email' => 'required | email',
            'password' => 'required',
        ]);

        $domain = strtolower(str_replace(' ', '_', $request->domain)) . '.localhost';
        $db_name = Str::slug($request->school_name, '_') . '_' . date('Ymdhis');
        $name = $request->school_name;

        DB::beginTransaction();
        try {

            DB::connection('landlord')->statement('CREATE DATABASE ' . $db_name);

            $tenant = Tenant::create([
                'name' => $name,
                'domain' => $domain,
                'database' => $db_name
            ]);

            $tenantId = $tenant->id;

            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'tenant_id' => $tenantId
            ]);
            Artisan::call('tenants:artisan "migrate --database=tenant"');
        } catch (QueryException $e) {
            return back()->with('danger', 'Something is wrong');
        }

        return back()->with('success', 'School registered Successfully.');

    }
}
