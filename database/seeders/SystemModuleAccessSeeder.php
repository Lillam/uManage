<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use App\Models\System\SystemModuleAccess;
use App\Models\System\SystemModuleAccessUser;

class SystemModuleAccessSeeder extends Seeder
{
    private Collection $systemModules;

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
        $this->systemModules = SystemModuleAccess::all();
        SystemModuleAccessUser::query()->where('user_id', '=', 1)->delete();
    }

    /**
    * Run the database seeders.
    *
    * @return void
    */
    public function run(): void
    {
        $bar = $this->command->getOutput()->createProgressBar($this->systemModules->count());
        foreach ($this->systemModules as $systemModuleAccess) {
            SystemModuleAccessUser::query()->create([
                'user_id'                 => 1,
                'system_module_access_id' => $systemModuleAccess->id,
                'is_enabled'              => true
            ]);
            $bar->advance();
        } $bar->finish();
    }
}
