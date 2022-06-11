<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use App\Models\System\SystemModule;
use App\Models\System\SystemModuleAccess;

class SystemModuleSeeder extends Seeder
{
    /**
     * @var Collection
     */
    private Collection $modules;

    /**
    * @var int
    */
    private int $system_module_access_id = 0;

    /**
    * SystemModuleSeeder constructor.
    */
    public function __construct()
    {
        $this->modules = collect(config('modules') ?? []);
    }

    /**
    * Run the database seeders.
    *
    * @return void
    */
    public function run()
    {
        // before this seeder executes, we are going to be deleting everything from the database, so that we can begin
        // re-syncing all the controllers that are going to be in the database.
        SystemModule::where('id', '>=', 1)->delete();

        $bar = $this->command->getOutput()->createProgressBar($this->modules->count());

        foreach ($this->modules as $id => $module) {
            SystemModule::create([
                'id'   => $id,
                'name' => $module->name
            ]);

            foreach ($module->controllers as $controller) {
                $methods = $this->extractAppControllerMethods($controller);
                if (! empty($methods)) {
                    foreach ($methods as $method) {
                        SystemModuleAccess::create([
                            'id'               => $this->incrementSystemModuleAccessId(),
                            'system_module_id' => $id,
                            'controller'       => $controller,
                            'method'           => $method
                        ]);
                    }
                }
            } $bar->advance();
        } $bar->finish();
    }

    /**
    * @return int
    */
    private function incrementSystemModuleAccessId(): int
    {
        return $this->system_module_access_id += 1;
    }

    /**
    * @param $controller
    * @return array
    */
    private function extractAppControllerMethods($controller): array
    {
        return ! empty($methods = get_class_methods($controller)) ? array_map(function($method) {
            return $method;
        }, array_filter($methods, function ($method) {
            return mb_substr_count($method, '_') === 1;
        })) : [];
    }
}
