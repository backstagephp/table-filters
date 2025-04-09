<?php

namespace Backstage\TableFilters\Commands;

use Filament\Facades\Filament;
use Filament\Panel;
use Filament\Support\Commands\Concerns\CanIndentStrings;
use Filament\Support\Commands\Concerns\CanManipulateFiles;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Symfony\Component\Console\Attribute\AsCommand;

use function Laravel\Prompts\error;
use function Laravel\Prompts\select;
use function Laravel\Prompts\suggest;
use function Laravel\Prompts\text;

#[AsCommand(name: 'make:filament-filter')]
class TableFiltersCommand extends Command
{
    use CanIndentStrings;
    use CanManipulateFiles;

    public $signature = 'make:filament-filter {name?} {--R|resource=} {--T|type=} {--panel=} {--F|force}';

    public $description = 'Create a new Filament filter class';

    public function handle()
    {
        $filter = (string) str(
            $this->argument('name') ??
                text(
                    label: 'What is the filter name?',
                    placeholder: 'VehicleFilter',
                    required: true,
                ),
        )
            ->trim('/')
            ->trim('\\')
            ->trim(' ')
            ->replace('/', '\\');

        if (! str($filter)->endsWith('Filter')) {
            $filter = str($filter)->append('Filter');
        }

        $filterClass = (string) str($filter)->afterLast('\\');

        $panel = $this->option('panel');

        if ($panel) {
            $panel = Filament::getPanel($panel, isStrict: false);
        }

        if (! $panel) {
            $panels = Filament::getPanels();

            /** @var Panel $panel */
            $panel = (count($panels) > 1) ? $panels[select(
                label: 'Which panel would you like to create this in?',
                options: array_map(
                    fn (Panel $panel): string => $panel->getId(),
                    $panels,
                ),
                default: Filament::getDefaultPanel()->getId()
            )] : Arr::first($panels);
        }

        $resourceDirectories = $panel
            ->getResourceDirectories();

        $resourceNamespaces = $panel
            ->getResourceNamespaces();

        foreach ($resourceDirectories as $resourceIndex => $resourceDirectory) {
            if (str($resourceDirectory)->startsWith(base_path('vendor'))) {
                unset($resourceDirectories[$resourceIndex]);
                unset($resourceNamespaces[$resourceIndex]);
            }
        }

        $resourceInput = $this->option('resource') ?? suggest(
            label: 'Which resource would you like to create this in?',
            options: collect($panel->getResources())
                ->filter(fn (string $namespace): bool => str($namespace)->contains('\\Resources\\') && str($namespace)->startsWith($resourceNamespaces))
                ->map(
                    fn (string $namespace): string => (string) str($namespace)
                        ->afterLast('\\Resources\\')
                )
                ->all(),
            placeholder: 'UserResource',
            required: true
        );

        $resourceName = $resourceInput;

        $filterNamespace = str($resourceNamespaces[0])
            ->append('\\')
            ->append($resourceName)
            ->append('\\Filters')
            ->toString();

        $sub = base_path('vendor/backstage/table-filters/stubs/Filter.php.stub');

        $stub = File::get($sub);

        $filterClasses = [
            'default' => [
                'namespace' => 'Filament\Tables\Filters\Filter',
                'class' => 'Filter',
            ],

            'select' => [
                'namespace' => 'Filament\Tables\Filters\SelectFilter',
                'class' => 'SelectFilter',
            ],

            'multiselect' => [
                'namespace' => 'Filament\Tables\Filters\MultiSelectFilter',
                'class' => 'MultiSelectFilter',
            ],

            'trashed' => [
                'namespace' => 'Filament\Tables\Filters\TrashedFilter',
                'class' => 'TrashedFilter',
            ],

            'ternary' => [
                'namespace' => 'Filament\Tables\Filters\TernaryFilter',
                'class' => 'TernaryFilter',
            ],
        ];

        $filterType = $this->option('type') ?? select(
            label: 'What type of filter would you like to create?',
            options: [
                'default' => 'Default',
                'select' => 'Select',
                'multiselect' => 'MultiSelect',
                'trashed' => 'Trashed',
                'ternary' => 'Ternary',
            ],
            default: 'default'
        );

        $stub = str($stub)
            ->replace('{{ filterNamespace }}', $filterClasses[$filterType]['namespace'])
            ->replace('{{ filterClass }}', $filterClasses[$filterType]['class'])
            ->replace('{{ namespace }}', $filterNamespace)
            ->replace('{{ class }}', $filterClass)
            ->toString();

        $filterPath = app()->path(str($filterNamespace)->after('App\\')->replace('\\', '/')->toString());

        $filterFile = str($filter)
            ->replace('\\', '/')
            ->append('.php')
            ->toString();

        $filterFilePath = str($filterPath)
            ->append('/')
            ->append($filterFile)
            ->toString();

        if (File::exists($filterFilePath) && ! $this->option('force')) {
            error('Filter already exists!');

            return 1;
        }

        if (! File::exists($filterPath)) {
            File::makeDirectory($filterPath, recursive: true);
        }

        File::put($filterFilePath, $stub);

        $this->components->info('Filter created successfully!');

        $this->components->info("Filter path: {$filterFilePath}");

        return static::SUCCESS;
    }
}
