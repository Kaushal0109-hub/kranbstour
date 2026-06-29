<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('monuments', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('name');
        });

        if (Schema::hasTable('monuments')) {
            foreach (DB::table('monuments')->get() as $row) {
                DB::table('monuments')->where('id', $row->id)->update([
                    'slug' => Str::slug($row->name),
                ]);
            }
        }

        Schema::table('monuments', function (Blueprint $table) {
            $table->unique(['category_id', 'slug']);
        });
    }

    public function down(): void
    {
        Schema::table('monuments', function (Blueprint $table) {
            $table->dropUnique(['category_id', 'slug']);
            $table->dropColumn('slug');
        });
    }
};
