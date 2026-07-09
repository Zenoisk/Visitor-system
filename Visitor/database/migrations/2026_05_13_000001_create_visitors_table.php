<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visitors', function (Blueprint $table) {
            $table->id();
            $table->string('visitor_no')->unique();
            $table->string('full_name');
            $table->string('ic_passport_no')->index();
            $table->string('phone', 40);
            $table->string('email')->nullable();
            $table->string('company_name');
            $table->text('purpose_of_visit');
            $table->string('person_to_meet');
            $table->string('department');
            $table->string('vehicle_plate_no')->nullable();
            $table->date('visit_date')->index();
            $table->string('status')->default('pending')->index();
            $table->text('remarks')->nullable();
            $table->timestamp('checked_in_at')->nullable();
            $table->timestamp('checked_out_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'visit_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visitors');
    }
};
