<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public $tableName = 'users';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique()->index();
            $table->string('password');
            $table->string('email')->unique()->index();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('avatar')->nullable();

            $table->enum('role', [
                'A', 'U', 'S'
            ])->default('U')->comment('A=admin, U=user, S=superUser');

            $table->timestamp('email_verified_at')->nullable();
            $table->engine = 'InnoDB';
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
        Schema::dropIfExists($this->tableName);
    }
}
