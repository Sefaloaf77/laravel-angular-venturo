<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserAuth extends Migration
{
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {
                Schema::create('user_auth', function (Blueprint $table) {
                        $table->uuid('id')->primary();
                        $table->integer('user_roles_id')
                                ->comment('Fill with id from table user_roles');
                        $table->string('name', 100)
                                ->comment('Fill with name of user');
                        $table->string('email', 50)
                                ->comment('Fill with user email for login');
                        $table->string('password', 255)
                                ->comment('Fill with user password');
                        $table->string('photo', 100)
                                ->comment('Fill with user profile picture')
                                ->nullable();
                        $table->timestamp('updated_security')
                                ->comment('Fill with timestamp when user update password / email')
                                ->nullable();
                        $table->timestamps();
                        $table->softDeletes();
                        $table->integer('created_by')->default(0);
                        $table->integer('updated_by')->default(0);
                        $table->integer('deleted_by')->default(0);
                        
                        $table->index('user_roles_id');
                        $table->index('email');
                        $table->index('name');
                        $table->index('updated_security');
                });
        }

        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down()
        {
                Schema::dropIfExists('user_auth');
        }
}
