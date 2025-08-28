<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('iuran', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->integer('nominal')->nullable(); // hanya untuk manual
            $table->date('tgl_tagih');
            $table->date('tgl_tempo');
            $table->enum('jenis', ['otomatis', 'manual']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('iuran');
    }
};
