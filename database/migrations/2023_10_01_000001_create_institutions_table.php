<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('institutions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('location')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();

            $table->string('alias');
            $table->string('db_name');
            $table->string('db_user');
            $table->string('db_password');
            $table->string('institution_id');

            $table->string('manager_email')->nullable();
            $table->string('manager_phone_number')->nullable();
            $table->string('it_email')->nullable();
            $table->string('it_phone_number')->nullable();

            //$table->softDeletes(); // enables soft deletes
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('institutions');
    }
};
