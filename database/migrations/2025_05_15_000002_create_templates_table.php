<?php


// database/migrations/2025_05_15_000002_create_templates_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTemplatesTable extends Migration
{
    public function up()
    {
        Schema::create('templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('template_category_id')->constrained('template_categories')->onDelete('cascade');
            $table->string('name'); // Display name
            $table->string('folder_name'); // Folder where template files are stored
            $table->string('thumbnail')->nullable(); // Path to thumbnail image
            $table->text('default_content')->nullable(); // JSON or HTML content
            $table->json('default_styles')->nullable(); // Colors etc stored as JSON
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('templates');
    }
}
