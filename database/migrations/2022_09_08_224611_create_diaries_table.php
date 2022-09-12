<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('diaries', function (Blueprint $table) {
            $table->id();
            $table->timestamp('datetime');
            $table->text('activity');
            $table->text('objective');
            $table->text('description');
            $table->string('state');
            $table->string('municipality');
            $table->string('place');
            $table->boolean('executed');
            $table->enum('wingspan', ['Ministerial', 'Vicepresidencia Sectorial', 'Vicepresidencia Ejecutiva', 'Presidencial', 'No aplica']);
            $table->text('observation');

            $table->foreignId('user_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreignId('institution_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('diaries');
    }
}
