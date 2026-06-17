<?php
 
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
 
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sso_users', function (Blueprint $table) {
            $table->id();
            $table->string('sub')->unique()->comment('Subject claim dari JWT SSO Dosen');
            $table->string('email')->unique();
            $table->string('name')->nullable();
            $table->string('local_role')->default('warga')->comment('Role lokal: warga, admin');
            $table->text('last_token')->nullable()->comment('JWT token terakhir');
            $table->timestamp('last_login_at')->nullable();
            $table->timestamps();
        });
    }
 
    public function down(): void
    {
        Schema::dropIfExists('sso_users');
    }
};
 