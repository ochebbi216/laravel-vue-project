<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\SoftDeletes;

class CreateReservationsTable extends Migration
{
    /**
     * Exécutez les migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_room');
            $table->unsignedBigInteger('id_user'); 
            $table->date('checkin');
            $table->date('checkout');
            $table->unsignedInteger('nbadulte');
            $table->unsignedInteger('nbenfants');
            $table->string('status')->default('en attente');
            $table->string('room_type'); // Assurez-vous que cela correspond à la typologie des chambres dans la table `rooms`
            $table->string('pension');
            $table->decimal('total_cost', 10, 2); // La précision et l'échelle peuvent être ajustées selon vos besoins
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('id_room')->references('id')->on('rooms')
                  ->onDelete('restrict')
                  ->onUpdate('cascade');
            $table->foreign('id_user')->references('id')->on('users')
                  ->onDelete('restrict')
                  ->onUpdate('cascade');
        });
    }

    /**
     * Inversez les migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reservations');
    }
}