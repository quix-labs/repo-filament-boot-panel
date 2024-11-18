<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->string("id")->primary();
            $table->string('path');
            $table->string('color');
            $table->timestamps();
        });


        DB::table('tenants')->insert([
            ['id' => 'tenant-1', 'path' => '/panel-1', 'color' => '#f97316'],
            ['id' => 'tenant-2', 'path' => '/panel-2', 'color' => '#059669'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
};
