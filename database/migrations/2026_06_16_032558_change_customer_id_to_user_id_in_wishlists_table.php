<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Drop the foreign key constraint
        $foreignKeyName = DB::select("
            SELECT CONSTRAINT_NAME
            FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE()
            AND TABLE_NAME = 'wishlists'
            AND COLUMN_NAME = 'customer_id'
            AND REFERENCED_TABLE_NAME IS NOT NULL
        ");

        if ($foreignKeyName && !empty($foreignKeyName)) {
            DB::statement("ALTER TABLE wishlists DROP FOREIGN KEY " . $foreignKeyName[0]->CONSTRAINT_NAME);
        }

        // Rename the column
        DB::statement("ALTER TABLE wishlists CHANGE customer_id user_id BIGINT UNSIGNED NOT NULL");

        // Add the new foreign key constraint
        DB::statement("ALTER TABLE wishlists ADD CONSTRAINT wishlists_user_id_foreign FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE");

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Drop the foreign key constraint
        $foreignKeyName = DB::select("
            SELECT CONSTRAINT_NAME
            FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE()
            AND TABLE_NAME = 'wishlists'
            AND COLUMN_NAME = 'user_id'
            AND REFERENCED_TABLE_NAME IS NOT NULL
        ");

        if ($foreignKeyName && !empty($foreignKeyName)) {
            DB::statement("ALTER TABLE wishlists DROP FOREIGN KEY " . $foreignKeyName[0]->CONSTRAINT_NAME);
        }

        // Rename the column back
        DB::statement("ALTER TABLE wishlists CHANGE user_id customer_id BIGINT UNSIGNED NOT NULL");

        // Add the old foreign key constraint
        DB::statement("ALTER TABLE wishlists ADD CONSTRAINT wishlists_customer_id_foreign FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE CASCADE");

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
};
