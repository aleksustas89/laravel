<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('shop_item_properties')) {
            Schema::create('shop_item_properties', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('tag_name');
                $table->integer('type');
                $table->integer('sorting')->default(0);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_item_properties');
    }
};
