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
        Schema::create('vendor_website_settings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('vendor_id')->constrained('vendors')->onDelete('cascade');
            $table->foreignId('template_id')->nullable()->constrained('templates')->onDelete('set null')->nullable();

            $table->string('logo')->nullable(); // Vendor logo path
            $table->string('favicon')->nullable(); // Favicon path

            $table->json('colors')->nullable(); // Primary, secondary colors etc.
            $table->json('custom_content')->nullable(); // Optional custom sections, like header text etc.

            $table->string('domain')->nullable(); // Custom domain if mapped
            $table->string('subdomain')->nullable(); // Subdomain like vendorname.yourdomain.com

            $table->boolean('status')->default(true); // Whether site is live or not

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor_website_settings');
    }
};
