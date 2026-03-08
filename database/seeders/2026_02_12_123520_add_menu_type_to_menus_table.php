<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMenuTypeToMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('menus', function (Blueprint $table) {
            $table->string('menu_type', 50)->nullable()->after('reference_id');
            // ou avec d'autres options :
            // $table->string('menu_type', 50)->default('regular');
            // $table->string('menu_type', 50)->after('nom_colonne');
            // $table->enum('menu_type', ['main', 'footer', 'sidebar'])->default('main');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('menus', function (Blueprint $table) {
            $table->dropColumn('menu_type');
        });
    }
}