<?php

namespace App\Modules\Installer\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Installer\Http\Requests\AdminAccountRequest;
use App\Modules\Installer\Http\Requests\DatabaseRequest;
use App\Modules\Installer\Http\Requests\SiteConfigRequest;
use App\Modules\Installer\Services\DatabaseInstaller;
use App\Modules\Installer\Services\EnvironmentWriter;
use App\Modules\Installer\Services\RequirementChecker;
use App\Modules\User\Database\Seeders\RolesPermissionsSeeder;
use App\Modules\User\Models\Role;
use App\Modules\User\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Inertia\Response;

class InstallerController extends Controller
{
    public function __construct(
        protected RequirementChecker $checker,
        protected DatabaseInstaller $dbInstaller,
        protected EnvironmentWriter $envWriter
    ) {}

    /**
     * Step 1: Welcome and Requirement Check.
     */
    public function index(): Response
    {
        return Inertia::render('Installer/Welcome', [
            'requirements' => $this->checker->check(),
        ]);
    }

    /**
     * Step 2: Database Configuration.
     */
    public function database(): Response
    {
        return Inertia::render('Installer/Database', [
            'current_config' => [
                'host' => env('DB_HOST', '127.0.0.1'),
                'port' => env('DB_PORT', '3306'),
                'database' => env('DB_DATABASE', ''),
                'username' => env('DB_USERNAME', ''),
                'prefix' => env('DB_PREFIX', 'ld_'),
            ]
        ]);
    }

    /**
     * Test database connection.
     */
    public function testConnection(DatabaseRequest $request)
    {
        $result = $this->dbInstaller->testConnection($request->validated());
        
        if ($result['passed']) {
            $this->envWriter->saveDatabaseConfig($request->validated());
        }

        return response()->json($result);
    }

    /**
     * Step 3: Migration Progress.
     */
    public function migration(): Response
    {
        return Inertia::render('Installer/Migration');
    }

    /**
     * Run migrations.
     */
    public function runMigration()
    {
        $result = $this->dbInstaller->runMigrations();
        
        return response()->json([
            'success' => $result['success'],
            'message' => $result['success'] ? 'Migrations completed successfully.' : 'Migration failed. Check logs.',
            'output'  => $result['output'] ?? '',
        ]);
    }

    /**
     * Step 4: Admin Account and Site Config.
     */
    public function account(): Response
    {
        return Inertia::render('Installer/Account', [
            'site_url' => url('/'),
        ]);
    }

    /**
     * Save admin account and site config.
     */
    public function saveAccount(AdminAccountRequest $adminReq, SiteConfigRequest $siteReq)
    {
        try {
            DB::beginTransaction();

            // 1. Save Site Config to .env
            $this->envWriter->saveSiteConfig($siteReq->validated());

            // 2. Run Seeders
            $seeder = new RolesPermissionsSeeder();
            $seeder->run();

            // 3. Create Admin User
            $user = User::create([
                'name' => $adminReq->name,
                'email' => $adminReq->email,
                'password' => $adminReq->password, // Form uses password hashing in model or we use Hash::make
                'status' => 'active',
            ]);

            // 4. Assign Super Admin Role
            $superAdminRole = Role::where('slug', 'super-admin')->first();
            if ($superAdminRole) {
                $user->roles()->attach($superAdminRole->id);
            }

            DB::commit();

            return redirect()->route('install.complete');
        } catch (Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Step 5: Installation Complete.
     */
    public function complete(): Response
    {
        // Mark as installed
        file_put_contents(storage_path('installed'), date('Y-m-d H:i:s'));

        return Inertia::render('Installer/Complete');
    }
}
