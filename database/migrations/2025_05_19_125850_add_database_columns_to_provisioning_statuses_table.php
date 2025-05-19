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
        Schema::table('provisioning_statuses', function (Blueprint $table) {
            if (!Schema::hasColumn('provisioning_statuses', 'db_name')) {
                $table->string('db_name')->nullable();
            }
            if (!Schema::hasColumn('provisioning_statuses', 'db_host')) {
                $table->string('db_host')->nullable();
            }
            if (!Schema::hasColumn('provisioning_statuses', 'db_user')) {
                $table->string('db_user')->nullable();
            }
            if (!Schema::hasColumn('provisioning_statuses', 'db_password')) {
                $table->string('db_password')->nullable();
            }
            if (!Schema::hasColumn('provisioning_statuses', 'manager_email')) {
                $table->string('manager_email')->nullable();
            }
            if (!Schema::hasColumn('provisioning_statuses', 'it_email')) {
                $table->string('it_email')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('provisioning_statuses', function (Blueprint $table) {
            $table->dropColumn([
                'db_name',
                'db_host',
                'db_user',
                'db_password',
                'manager_email',
                'it_email'
            ]);
        });
    }
};
