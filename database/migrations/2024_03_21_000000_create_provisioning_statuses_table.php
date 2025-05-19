<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('provisioning_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('alias')->unique();
            $table->string('status');
            $table->string('step')->nullable();
            $table->text('message')->nullable();
            $table->json('data')->nullable();
            $table->string('db_name')->nullable();
            $table->string('db_host')->nullable();
            $table->string('db_user')->nullable();
            $table->string('db_password')->nullable();
            $table->string('manager_email')->nullable();
            $table->string('it_email')->nullable();
            $table->timestamp('started_at');
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('provisioning_statuses');
    }
};
