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
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->nullable()->unique()->after('name');
        });

        // Set usernames for existing users
        foreach (\App\Models\User::all() as $user) {
            if ($user->email === 'admin@airod.test') {
                $user->username = 'admin';
            } elseif ($user->email === 'security@airod.test') {
                $user->username = 'security';
            } else {
                $user->username = explode('@', $user->email)[0];
            }
            $user->save();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('username');
        });
    }
};
