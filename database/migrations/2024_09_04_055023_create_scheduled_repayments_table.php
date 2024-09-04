<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScheduledRepaymentsTable extends Migration
{
    public function up()
    {
        Schema::create('scheduled_repayments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loan_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 15, 2);
            $table->date('due_date');
            $table->string('status')->default('pending'); // Menambahkan kolom status
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('scheduled_repayments');
    }
}
