<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('tagline')->nullable();
            $table->string('icon')->default('fa-map-marker-alt');
            $table->text('description')->nullable();
            $table->json('home_highlights')->nullable();
            $table->string('tour_count_label')->nullable();
            $table->string('card_image')->nullable();
            $table->string('banner_image')->nullable();
            $table->string('route_name')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_spotlight')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('tour_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('city_id')->nullable()->constrained()->nullOnDelete();
            $table->string('key');
            $table->string('slug')->unique();
            $table->string('city_name');
            $table->string('title');
            $table->string('heading');
            $table->string('tagline')->nullable();
            $table->string('icon')->default('fa-route');
            $table->text('description')->nullable();
            $table->string('banner_image')->nullable();
            $table->string('card_image')->nullable();
            $table->string('tour_count_label')->nullable();
            $table->string('route_name')->nullable();
            $table->string('map_query')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('tour_packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('tour_categories')->cascadeOnDelete();
            $table->string('slug');
            $table->string('title');
            $table->string('duration');
            $table->decimal('price', 10, 2);
            $table->string('price_display')->nullable();
            $table->decimal('rating', 2, 1)->default(4.8);
            $table->string('tag')->nullable();
            $table->string('image')->nullable();
            $table->text('summary')->nullable();
            $table->text('description')->nullable();
            $table->longText('full_description')->nullable();
            $table->unsignedInteger('review_count')->default(1450);
            $table->boolean('is_featured')->default(false);
            $table->string('featured_section')->nullable();
            $table->unsignedInteger('featured_order')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->unique(['category_id', 'slug']);
        });

        Schema::create('monuments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('tour_categories')->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('package_highlights', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained('tour_packages')->cascadeOnDelete();
            $table->string('text');
            $table->unsignedInteger('sort_order')->default(0);
        });

        Schema::create('package_itineraries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained('tour_packages')->cascadeOnDelete();
            $table->unsignedTinyInteger('day_number')->default(1);
            $table->string('title');
            $table->text('description')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
        });

        Schema::create('package_inclusions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained('tour_packages')->cascadeOnDelete();
            $table->string('text');
            $table->unsignedInteger('sort_order')->default(0);
        });

        Schema::create('package_exclusions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained('tour_packages')->cascadeOnDelete();
            $table->string('text');
            $table->unsignedInteger('sort_order')->default(0);
        });

        Schema::create('package_faqs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained('tour_packages')->cascadeOnDelete();
            $table->string('question');
            $table->text('answer');
            $table->unsignedInteger('sort_order')->default(0);
        });

        Schema::create('package_location_tags', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained('tour_packages')->cascadeOnDelete();
            $table->string('tag');
            $table->unsignedInteger('sort_order')->default(0);
        });

        Schema::create('category_related', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('tour_categories')->cascadeOnDelete();
            $table->foreignId('related_category_id')->constrained('tour_categories')->cascadeOnDelete();
            $table->unsignedInteger('sort_order')->default(0);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('category_related');
        Schema::dropIfExists('package_location_tags');
        Schema::dropIfExists('package_faqs');
        Schema::dropIfExists('package_exclusions');
        Schema::dropIfExists('package_inclusions');
        Schema::dropIfExists('package_itineraries');
        Schema::dropIfExists('package_highlights');
        Schema::dropIfExists('monuments');
        Schema::dropIfExists('tour_packages');
        Schema::dropIfExists('tour_categories');
        Schema::dropIfExists('cities');
    }
};
