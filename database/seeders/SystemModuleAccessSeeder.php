<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\System\SystemModuleAccess;
use App\Models\System\SystemModuleAccessUser;

class SystemModuleAccessSeeder extends Seeder
{
    private $system_modules;

    /**
    * Upon on the construction of this seeder, we are just going to execute a variety of code that will allow this
    * seeder to continue working as expected without a hitch... any dependencies for this seeder will be handled
    * within the construction of the class.
    *
    * SystemModuleAccessSeeder constructor.
    * @return void
    */
    public function __construct()
    {
        $this->system_modules = SystemModuleAccess::all();
        SystemModuleAccessUser::where('user_id', '=', 1)->delete();
    }

    /**
    * Run the database seeders.
    *
    * @return void
    */
    public function run()
    {
        $bar = $this->command->getOutput()->createProgressBar($this->system_modules->count());
        foreach ($this->system_modules as $system_module_access) {
            SystemModuleAccessUser::create([
                'user_id'                 => 1,
                'system_module_access_id' => $system_module_access->id,
                'is_enabled'              => true
            ]);
            $bar->advance();
        } $bar->finish();
    }
}