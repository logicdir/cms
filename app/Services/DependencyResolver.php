<?php

namespace App\Services;

use App\Contracts\ModuleInterface;
use Illuminate\Support\Collection;
use Exception;

/**
 * Class DependencyResolver
 *
 * Handles topological sorting and circular dependency detection for modules.
 */
class DependencyResolver
{
    /**
     * Resolve and sort modules by dependency order.
     * 
     * @param Collection<int, ModuleInterface> $modules
     * @return Collection<int, ModuleInterface>
     * @throws Exception
     */
    public function sort(Collection $modules): Collection
    {
        $sorted = collect();
        $visited = [];
        $temp = [];

        foreach ($modules as $module) {
            $this->visit($module, $modules, $visited, $temp, $sorted);
        }

        return $sorted;
    }

    /**
     * Recursive visit for topological sort (DFS).
     */
    protected function visit(
        ModuleInterface $now,
        Collection $all,
        array &$visited,
        array &$temp,
        Collection &$sorted
    ): void {
        $slug = $now->getSlug();

        if (isset($temp[$slug])) {
            throw new Exception("Circular dependency detected involving module: {$slug}");
        }

        if (isset($visited[$slug])) {
            return;
        }

        $temp[$slug] = true;

        foreach ($now->getDependencies() as $dependentSlug => $versionRange) {
            $dependency = $all->first(fn(ModuleInterface $m) => $m->getSlug() === $dependentSlug);
            
            if (!$dependency) {
                // In a production system, we might throw an error or log a warning.
                // For now, we'll continue if it's optional, but here we assume required.
                continue;
            }

            $this->visit($dependency, $all, $visited, $temp, $sorted);
        }

        unset($temp[$slug]);
        $visited[$slug] = true;
        $sorted->push($now);
    }

    /**
     * Check if version satisfies constraint (Simplified semver match).
     */
    public function satisfies(string $version, string $constraint): bool
    {
        // For production, use composer/semver or similar.
        // Simplified check for now.
        if ($constraint === '*' || empty($constraint)) {
            return true;
        }

        return version_compare($version, ltrim($constraint, '^~=>'), '>=');
    }
}
