<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoomsTable extends Migration
{
    public function up()
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->integer('room_number')->unique();
            $table->enum('room_type', ['Single', 'Double', 'Triple','Suite']);
            $table->string('image')->nullable(); // The path to the room's image, nullable if no image is set.
            $table->text('description')->nullable(); // A description for the room, which is optional.
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rooms');
    }
}