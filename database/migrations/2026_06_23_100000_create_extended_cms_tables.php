<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('home_heroes', function (Blueprint $table) {
            $table->id();
            $table->string('badge_text')->nullable();
            $table->string('rating_text')->nullable();
            $table->string('heading_line1');
            $table->string('heading_line2')->nullable();
            $table->text('subtitle')->nullable();
            $table->string('search_placeholder')->nullable();
            $table->string('background_image')->nullable();
            $table->json('thumbnail_keys')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('home_process_steps', function (Blueprint $table) {
            $table->id();
            $table->string('icon')->default('fa-star');
            $table->string('color_classes')->nullable();
            $table->string('step_number', 5)->nullable();
            $table->string('title');
            $table->text('text');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('home_promo_sections', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('badge')->nullable();
            $table->string('title')->nullable();
            $table->string('subtitle')->nullable();
            $table->text('description')->nullable();
            $table->json('tags')->nullable();
            $table->string('price_label')->nullable();
            $table->string('cta_label')->nullable();
            $table->string('cta_route')->nullable();
            $table->string('secondary_cta_label')->nullable();
            $table->string('secondary_cta_route')->nullable();
            $table->string('category_slug')->nullable();
            $table->json('city_keys')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('package_gallery_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained('tour_packages')->cascadeOnDelete();
            $table->string('image');
            $table->string('alt')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
        });

        Schema::create('package_features', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained('tour_packages')->cascadeOnDelete();
            $table->string('icon')->default('fa-check');
            $table->string('color_classes')->nullable();
            $table->string('title');
            $table->text('description')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
        });

        Schema::create('package_important_infos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained('tour_packages')->cascadeOnDelete();
            $table->string('heading');
            $table->string('item_text');
            $table->unsignedInteger('sort_order')->default(0);
        });

        Schema::create('social_links', function (Blueprint $table) {
            $table->id();
            $table->string('icon');
            $table->string('label');
            $table->string('url')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('cms_pages', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('heading');
            $table->longText('content')->nullable();
            $table->boolean('show_in_footer')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::table('tour_categories', function (Blueprint $table) {
            $table->boolean('show_in_nav')->default(true)->after('is_active');
            $table->string('nav_label')->nullable()->after('show_in_nav');
        });
    }

    public function down(): void
    {
        Schema::table('tour_categories', function (Blueprint $table) {
            $table->dropColumn(['show_in_nav', 'nav_label']);
        });
        Schema::dropIfExists('cms_pages');
        Schema::dropIfExists('social_links');
        Schema::dropIfExists('package_important_infos');
        Schema::dropIfExists('package_features');
        Schema::dropIfExists('package_gallery_images');
        Schema::dropIfExists('home_promo_sections');
        Schema::dropIfExists('home_process_steps');
        Schema::dropIfExists('home_heroes');
    }
};
