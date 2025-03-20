<?php

namespace Hanafalah\ModuleMedicService;

use Hanafalah\ModuleMedicService\{
    Models\MedicService,
    Schemas\MedicService as SchemaMedicService,
};
use Hanafalah\LaravelSupport\Providers\BaseServiceProvider;

class ModuleMedicServiceServiceProvider extends BaseServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerMainClass(ModuleMedicService::class)
            ->registerCommandService(Providers\CommandServiceProvider::class)
            ->registers([
                '*',
                'Services'  => function () {
                    $this->binds([
                        Contracts\ModuleMedicService::class  => MedicService::class,
                        Contracts\MedicService::class        => SchemaMedicService::class,
                    ]);
                },
            ]);
    }

    protected function dir(): string
    {
        return __DIR__ . '/';
    }

    protected function migrationPath(string $path = ''): string
    {
        return database_path($path);
    }
}
