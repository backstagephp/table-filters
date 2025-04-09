<?php

namespace Backstage\TableFilters;

use Filament\Tables\Table;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;
use Spatie\LaravelPackageTools\Package;
use Livewire\Features\SupportTesting\Testable;
use Backstage\TableFilters\Testing\TestsTableFilters;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Backstage\TableFilters\Commands\TableFiltersCommand;
use Filament\Resources\RelationManagers\RelationManager;

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


        Table::macro('withFileBasedFilters', function () {
            $baseFilters = $this->getFilters();

            $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 10);

            $callerFile = collect($trace)
                ->map(fn($trace) => $trace['file'] ?? null)
                ->filter()
                ->filter(fn($file) => preg_match('#.*/Resources/([A-Za-z0-9]+)Resource\.php$#', $file))
                ->first();

            if (! $callerFile) {
                return $this->filters($baseFilters);
            }
            
            $namespace = str($callerFile)
                ->after('app/')
                ->beforeLast('/')
                ->replace('/', '\\')
                ->prepend('App\\')
                ->toString();

            $className = str($callerFile)
                ->afterLast('/')
                ->before('.php')
                ->replace('/', '\\')
                ->toString();

            $resource = str($namespace)
                ->append('\\')
                ->append($className)
                ->toString();

            $cacheKey = 'table_filters_' . str($resource)->afterLast('\\')->snake()->toString();

            $filterNamespace = str($resource)->append('\\Filters')->toString();
            $filterPath = app_path(str($filterNamespace)->after('App\\')->replace('\\', '/')->toString());

            $filterClassNames = collect(File::files($filterPath))
                ->map(fn($file) => $filterNamespace . '\\' . $file->getFilenameWithoutExtension())
                ->filter(fn($class) => class_exists($class))
                ->values()
                ->toArray();

            $filterClasses = app()->environment('production')
                ? Cache::rememberForever($cacheKey, fn() => $filterClassNames)
                : $filterClassNames;

            $fileBasedFilters = collect($filterClasses)
                ->filter(fn($class) => class_exists($class))
                ->map(fn($class) => $class::make($class)->name(
                    str($class)->afterLast('\\')->before('Filter')->kebab()->toString()
                ))
                ->toArray();

            $filters = array_merge($baseFilters, $fileBasedFilters);

            return $this->filters($filters);
        });
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
