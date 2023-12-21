<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\SoftDeletes;

class CreateReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_room');
            $table->unsignedBigInteger('id_user');
            $table->date('check_in');
            $table->date('check_out');
            $table->integer('number_adults'); // Number of adults
            $table->integer('number_children'); // Number of children
            $table->string('status')->default('en attente');
            $table->enum('type', ['demi-pension', 'pension-compléte', 'Lpd', 'tout inclus']);
            $table->decimal('price_adult', 8, 2); // Price per adult
            $table->decimal('price_child', 8, 2); // Price per child
            $table->timestamps();
            $table->softDeletes();

            // Foreign key constraints
            $table->foreign('id_room')->references('id')->on('rooms')
                  ->onDelete('restrict')
                  ->onUpdate('cascade');
            $table->foreign('id_user')->references('id')->on('users')
                  ->onDelete('restrict')
                  ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Drop the reservations table
        Schema::dropIfExists('reservations');
    }
}