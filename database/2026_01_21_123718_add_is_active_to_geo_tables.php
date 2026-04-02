<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected array $tablesWithLongitude = [
        'countries',
        'regions',
        'provinces',
        'villes',
        'secteurs',
    ];

    protected string $continentTable = 'continents';

    public function up(): void
    {
        // Continents → before languages
        Schema::table($this->continentTable, function (Blueprint $table) {
            if (!Schema::hasColumn('continents', 'is_active')) {
                $table->boolean('is_active')
                    ->nullable()
                    ->default(false)
                    ->before('languages');
            }
        });

        // Other geo tables → after longitude
        foreach ($this->tablesWithLongitude as $tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                if (!Schema::hasColumn($tableName, 'is_active')) {
                    $table->boolean('is_active')
                        ->nullable()
                        ->default(false)
                        ->after('longitude');
                }
            });
        }
    }

    public function down(): void
    {
        Schema::table($this->continentTable, function (Blueprint $table) {
            if (Schema::hasColumn('continents', 'is_active')) {
                $table->dropColumn('is_active');
            }
        });

        foreach ($this->tablesWithLongitude as $tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                if (Schema::hasColumn($tableName, 'is_active')) {
                    $table->dropColumn('is_active');
                }
            });
        }
    }
};
