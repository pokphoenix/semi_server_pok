<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUsersRelationships extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function(Blueprint $table) {
            $table->foreign('role_id')->references('id')->on('roles')
                ->onDelete('restrict')
                ->onUpdate('cascade');
        });
        Schema::table('users', function(Blueprint $table) {
            $table->foreign('branch_id')->references('id')->on('branches')
                ->onDelete('restrict')
                ->onUpdate('cascade');
        });
        Schema::table('users', function(Blueprint $table) {
            $table->foreign('user_type_id')->references('id')->on('user_type')
                ->onDelete('restrict')
                ->onUpdate('cascade');
        });
        Schema::table('perm_role', function(Blueprint $table) {
            $table->foreign('role_id')->references('id')->on('roles')
                ->onDelete('restrict')
                ->onUpdate('cascade');
        });
        Schema::table('perm_role', function(Blueprint $table) {
            $table->foreign('permission_id')->references('id')->on('permissions')
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
        Schema::table('users', function(Blueprint $table) {
            $table->dropForeign('users_role_id_foreign');
        });
        Schema::table('users', function(Blueprint $table) {
            $table->dropForeign('users_branch_id_foreign');
        });
        Schema::table('users', function(Blueprint $table) {
            $table->dropForeign('users_user_type_id_foreign');
        });
        Schema::table('perm_role', function(Blueprint $table) {
            $table->dropForeign('perm_role_role_id_foreign');
        });
        Schema::table('perm_role', function(Blueprint $table) {
            $table->dropForeign('perm_role_permission_id_foreign');
        });
    }
}
