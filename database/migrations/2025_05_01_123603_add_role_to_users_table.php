<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration
{
 /**
 * Run the migrations.
 */
 public function up(): void
 {
 Schema::table('users', function (Blueprint $table) {
 //Add new column "role"
$table->string('role', 15)->default('Student')->after('password')->nullable(false);
 });
 }
 /**
 * Reverse the migrations.
 */
 public function down(): void
 {
 Schema::table('users', function (Blueprint $table) {
 //add the rollback option
 $table->dropColumn('role');
 });
 }
};