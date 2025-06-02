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
        Schema::create('custom_forms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('custom_forms_entities', function (Blueprint $table) {
            $table->id();
            $table->integer('form_id');
            $table->string('entity');
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        Schema::create('custom_forms_fields', function (Blueprint $table) {
            $table->id();
            $table->integer('form_id');
            $table->integer('parent_id')->nullable();
            $table->integer('order')->default(0);
            $table->string('type');
            $table->json('config');
        });

        Schema::create('custom_forms_values', function (Blueprint $table) {
            $table->id();
            $table->integer('entity_id');
            $table->integer('field_id');
            $table->text('value');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_forms');
        Schema::dropIfExists('custom_forms_entities');
        Schema::dropIfExists('custom_forms_fields');
        Schema::dropIfExists('custom_forms_values');
    }
};
