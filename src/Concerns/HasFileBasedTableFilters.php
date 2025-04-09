<?php

namespace Backstage\TableFilters\Concerns;

use Filament\Tables\Table;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

trait HasFileBasedTableFilters
{
    /**
     * Get the table with filters
     *
     * Derived from Filament's InteractsWithTable trait.
     *
     * @see \Filament\Tables\Concerns\InteractsWithTable::table()
     */
    public function table(Table $table): Table
    {
        /**
         * @var Table $table
         */
        $table = parent::table($table);

        $filters = $this->getFilters();

        $table = $table->filters([...$filters]);

        return $table;
    }

    /**
     * Get the filters for the table
     */
    protected function getFilters(): array
    {
        $resource = static::getResource();
        $cacheKey = $this->getFilterCacheKey($resource);

        $filterClasses = app()->environment('production')
            ? Cache::rememberForever($cacheKey, fn() => $this->resolveFilterClassNames($resource))
            : $this->resolveFilterClassNames($resource);

        return collect($filterClasses)
            ->filter(fn($class) => class_exists($class))
            ->map(fn($class) => $class::make($class)->name(
                str($class)->afterLast('\\')->before('Filter')->kebab()->toString()
            ))
            ->toArray();
    }

    /**
     * Get the cache key for the filters
     */
    protected function getFilterCacheKey(string $resource): string
    {
        return 'table_filters_' . str($resource)->afterLast('\\')->snake()->toString();
    }

    /**
     * Resolve the filter class names (safe to cache)
     */
    protected function resolveFilterClassNames(string $resource): array
    {
        $filterNamespace = str($resource)->append('\\Filters')->toString();
        $filterPath = app_path(str($filterNamespace)->after('App\\')->replace('\\', '/')->toString());

        return collect(File::files($filterPath))
            ->map(fn($file) => $filterNamespace . '\\' . $file->getFilenameWithoutExtension())
            ->filter(fn($class) => class_exists($class))
            ->values()
            ->toArray();
    }
}
