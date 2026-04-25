<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CanadaGeoFromSqlSeeder extends Seeder
{
    /**
     * Import geographic data from a phpMyAdmin SQL dump.
     *
     * Supported tables (in order): continents, countries, provinces, regions, secteurs, villes.
     */
    public function run(): void
    {
        $sqlPath = $this->resolveSqlPath();

        if (!$sqlPath || !is_file($sqlPath)) {
            $this->command?->error('SQL introuvable. Configurez CANADA_GEO_SQL_PATH ou placez le fichier dans database/seeders/data.');
            return;
        }

        $sql = file_get_contents($sqlPath);
        if ($sql === false || trim($sql) === '') {
            $this->command?->error('Le fichier SQL est vide.');
            return;
        }

        $tables = ['continents', 'countries', 'provinces', 'regions', 'secteurs', 'villes'];
        $importStats = [];

        DB::beginTransaction();

        try {
            foreach ($tables as $table) {
                $rows = $this->extractRowsForTable($sql, $table);
                $importStats[$table] = count($rows);

                foreach ($rows as $row) {
                    if (!isset($row['id'])) {
                        continue;
                    }

                    $id = (int) $row['id'];
                    DB::table($table)->updateOrInsert(
                        ['id' => $id],
                        $this->sanitizeRow($row)
                    );
                }
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->command?->error('Erreur import SQL: ' . $e->getMessage());
            return;
        }

        $this->command?->info('Import SQL termine depuis: ' . $sqlPath);
        foreach ($tables as $table) {
            $this->command?->line("- {$table}: {$importStats[$table]} ligne(s) importee(s)");
        }

        if (($importStats['villes'] ?? 0) === 0) {
            $this->command?->warn('Aucune ligne villes detectee dans ce SQL.');
        }
    }

    private function resolveSqlPath(): ?string
    {
        $candidates = array_filter([
            env('CANADA_GEO_SQL_PATH'),
            database_path('seeders/data/go_exploria.sql'),
            database_path('seeders/data/go_exploria (2).sql'),
            'C:\\Users\\Wahid Fkiri\\Downloads\\go_exploria (2).sql',
        ]);

        foreach ($candidates as $path) {
            if (is_file($path)) {
                return $path;
            }
        }

        return null;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function extractRowsForTable(string $sql, string $table): array
    {
        $rows = [];

        $pattern = '/INSERT INTO\s+`' . preg_quote($table, '/') . '`\s*\((.*?)\)\s*VALUES\s*(.*?);/is';
        if (!preg_match_all($pattern, $sql, $matches, PREG_SET_ORDER)) {
            return $rows;
        }

        foreach ($matches as $match) {
            $columns = array_map(
                static fn ($c) => trim(str_replace('`', '', $c)),
                explode(',', $match[1])
            );

            $tuples = $this->splitSqlTuples($match[2]);
            foreach ($tuples as $tuple) {
                $values = str_getcsv($tuple, ',', "'", "\\");
                if (count($values) !== count($columns)) {
                    continue;
                }

                $row = [];
                foreach ($columns as $idx => $column) {
                    $row[$column] = $this->normalizeSqlValue($values[$idx]);
                }

                $rows[] = $row;
            }
        }

        return $rows;
    }

    /**
     * Split "VALUES (...), (...), (...)" into tuple payloads without outer parentheses.
     *
     * @return array<int, string>
     */
    private function splitSqlTuples(string $valuesChunk): array
    {
        $tuples = [];
        $len = strlen($valuesChunk);
        $inQuote = false;
        $escape = false;
        $depth = 0;
        $current = '';

        for ($i = 0; $i < $len; $i++) {
            $ch = $valuesChunk[$i];

            if ($escape) {
                $current .= $ch;
                $escape = false;
                continue;
            }

            if ($ch === '\\') {
                $current .= $ch;
                $escape = true;
                continue;
            }

            if ($ch === "'") {
                $inQuote = !$inQuote;
                $current .= $ch;
                continue;
            }

            if (!$inQuote && $ch === '(') {
                if ($depth > 0) {
                    $current .= $ch;
                }
                $depth++;
                continue;
            }

            if (!$inQuote && $ch === ')') {
                $depth--;
                if ($depth === 0) {
                    $tuples[] = trim($current);
                    $current = '';
                } else {
                    $current .= $ch;
                }
                continue;
            }

            if ($depth > 0) {
                $current .= $ch;
            }
        }

        return $tuples;
    }

    private function normalizeSqlValue(?string $value): mixed
    {
        if ($value === null) {
            return null;
        }

        $trimmed = trim($value);
        if (strcasecmp($trimmed, 'NULL') === 0) {
            return null;
        }

        if ($trimmed === '') {
            return '';
        }

        // Keep strings as-is for timestamps/text/json-like fields.
        if (is_numeric($trimmed)) {
            if (str_contains($trimmed, '.')) {
                return (float) $trimmed;
            }
            return (int) $trimmed;
        }

        return $trimmed;
    }

    /**
     * Keep only columns that currently exist on target table to avoid schema mismatch.
     */
    private function sanitizeRow(array $row): array
    {
        return array_map(
            static function ($v) {
                if (is_string($v)) {
                    return str_replace(["\r\n", "\r"], "\n", $v);
                }
                return $v;
            },
            $row
        );
    }
}

