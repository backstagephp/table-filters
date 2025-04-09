<?php

namespace Backstage\TableFilters;

use Backstage\TableFilters\Commands\TableFiltersCommand;
use Backstage\TableFilters\Testing\TestsTableFilters;
use Livewire\Features\SupportTesting\Testable;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class TableFiltersServiceProvider extends PackageServiceProvider
{
    public static string $name = 'table-filters';

    public static string $viewNamespace = 'table-filters';

    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package->name(static::$name)
            ->hasCommands($this->getCommands())
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->publishConfigFile()
                    ->publishMigrations()
                    ->askToRunMigrations()
                    ->askToStarRepoOnGitHub('backstage/table-filters');
            });

        $configFileName = 'backstage/table-filters';

        if (file_exists($package->basePath("/../config/{$configFileName}.php"))) {
            $package->hasConfigFile($configFileName);
        }
    }

    public function packageRegistered(): void {}

    public function packageBooted(): void
    {
        // Testing
        Testable::mixin(new TestsTableFilters);
    }

    protected function getAssetPackageName(): ?string
    {
        return 'backstage/table-filters';
    }

    /**
     * @return array<class-string>
     */
    protected function getCommands(): array
    {
        return [
            TableFiltersCommand::class,
        ];
    }

    /**
     * @return array<string>
     */
    protected function getIcons(): array
    {
        return [];
    }

    /**
     * @return array<string>
     */
    protected function getRoutes(): array
    {
        return [];
    }

    /**
     * @return array<string, mixed>
     */
    protected function getScriptData(): array
    {
        return [];
    }

    /**
     * @return array<string>
     */
    protected function getMigrations(): array
    {
        return [
            'create_table-filters_table',
        ];
    }
}
