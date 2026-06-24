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
        // Check if columns already exist (from partial migration)
        $stripeSessionIdExists = DB::select("
            SELECT COLUMN_NAME
            FROM INFORMATION_SCHEMA.COLUMNS
            WHERE TABLE_SCHEMA = DATABASE()
            AND TABLE_NAME = 'orders'
            AND COLUMN_NAME = 'stripe_session_id'
        ");

        $shippingCityExists = DB::select("
            SELECT COLUMN_NAME
            FROM INFORMATION_SCHEMA.COLUMNS
            WHERE TABLE_SCHEMA = DATABASE()
            AND TABLE_NAME = 'orders'
            AND COLUMN_NAME = 'shipping_city'
        ");

        $shippingPhoneExists = DB::select("
            SELECT COLUMN_NAME
            FROM INFORMATION_SCHEMA.COLUMNS
            WHERE TABLE_SCHEMA = DATABASE()
            AND TABLE_NAME = 'orders'
            AND COLUMN_NAME = 'shipping_phone'
        ");

        $busGateExists = DB::select("
            SELECT COLUMN_NAME
            FROM INFORMATION_SCHEMA.COLUMNS
            WHERE TABLE_SCHEMA = DATABASE()
            AND TABLE_NAME = 'orders'
            AND COLUMN_NAME = 'bus_gate'
        ");

        // Only add columns that don't exist
        Schema::table('orders', function (Blueprint $table) use ($stripeSessionIdExists, $shippingCityExists, $shippingPhoneExists, $busGateExists) {
            if (!$stripeSessionIdExists || empty($stripeSessionIdExists)) {
                $table->string('stripe_session_id')->nullable();
            }
            if (!$shippingCityExists || empty($shippingCityExists)) {
                $table->string('shipping_city')->nullable();
            }
            if (!$shippingPhoneExists || empty($shippingPhoneExists)) {
                $table->string('shipping_phone')->nullable();
            }
            if (!$busGateExists || empty($busGateExists)) {
                $table->string('bus_gate')->nullable();
            }
        });

        // Check if customer_id column exists in the orders table
        $columnExists = DB::select("
            SELECT COLUMN_NAME
            FROM INFORMATION_SCHEMA.COLUMNS
            WHERE TABLE_SCHEMA = DATABASE()
            AND TABLE_NAME = 'orders'
            AND COLUMN_NAME = 'customer_id'
        ");

        // Only perform the rename if customer_id exists
        if ($columnExists && !empty($columnExists)) {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            // Drop the foreign key constraint
            $foreignKeyName = DB::select("
                SELECT CONSTRAINT_NAME
                FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
                WHERE TABLE_SCHEMA = DATABASE()
                AND TABLE_NAME = 'orders'
                AND COLUMN_NAME = 'customer_id'
                AND REFERENCED_TABLE_NAME IS NOT NULL
            ");

            if ($foreignKeyName && !empty($foreignKeyName)) {
                DB::statement("ALTER TABLE orders DROP FOREIGN KEY " . $foreignKeyName[0]->CONSTRAINT_NAME);
            }

            // Rename the column
            DB::statement("ALTER TABLE orders CHANGE customer_id user_id BIGINT UNSIGNED NOT NULL");

            // Add the new foreign key constraint
            DB::statement("ALTER TABLE orders ADD CONSTRAINT orders_user_id_foreign FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE");

            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        } else {
            // If customer_id doesn't exist, check if user_id exists and add foreign key if needed
            $userColumnExists = DB::select("
                SELECT COLUMN_NAME
                FROM INFORMATION_SCHEMA.COLUMNS
                WHERE TABLE_SCHEMA = DATABASE()
                AND TABLE_NAME = 'orders'
                AND COLUMN_NAME = 'user_id'
            ");

            if ($userColumnExists && !empty($userColumnExists)) {
                DB::statement('SET FOREIGN_KEY_CHECKS=0;');

                // Check if foreign key already exists
                $foreignKeyExists = DB::select("
                    SELECT CONSTRAINT_NAME
                    FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
                    WHERE TABLE_SCHEMA = DATABASE()
                    AND TABLE_NAME = 'orders'
                    AND COLUMN_NAME = 'user_id'
                    AND REFERENCED_TABLE_NAME IS NOT NULL
                ");

                if (!$foreignKeyExists || empty($foreignKeyExists)) {
                    DB::statement("ALTER TABLE orders ADD CONSTRAINT orders_user_id_foreign FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE");
                }

                DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            }
        }
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
            AND TABLE_NAME = 'orders'
            AND COLUMN_NAME = 'user_id'
            AND REFERENCED_TABLE_NAME IS NOT NULL
        ");

        if ($foreignKeyName && !empty($foreignKeyName)) {
            DB::statement("ALTER TABLE orders DROP FOREIGN KEY " . $foreignKeyName[0]->CONSTRAINT_NAME);
        }

        // Rename the column back (only if user_id exists)
        $userColumnExists = DB::select("
            SELECT COLUMN_NAME
            FROM INFORMATION_SCHEMA.COLUMNS
            WHERE TABLE_SCHEMA = DATABASE()
            AND TABLE_NAME = 'orders'
            AND COLUMN_NAME = 'user_id'
        ");

        if ($userColumnExists && !empty($userColumnExists)) {
            DB::statement("ALTER TABLE orders CHANGE user_id customer_id BIGINT UNSIGNED NOT NULL");

            // Add the old foreign key constraint
            DB::statement("ALTER TABLE orders ADD CONSTRAINT orders_customer_id_foreign FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE CASCADE");
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Check if columns exist before dropping them
        $stripeSessionIdExists = DB::select("
            SELECT COLUMN_NAME
            FROM INFORMATION_SCHEMA.COLUMNS
            WHERE TABLE_SCHEMA = DATABASE()
            AND TABLE_NAME = 'orders'
            AND COLUMN_NAME = 'stripe_session_id'
        ");

        $shippingCityExists = DB::select("
            SELECT COLUMN_NAME
            FROM INFORMATION_SCHEMA.COLUMNS
            WHERE TABLE_SCHEMA = DATABASE()
            AND TABLE_NAME = 'orders'
            AND COLUMN_NAME = 'shipping_city'
        ");

        $shippingPhoneExists = DB::select("
            SELECT COLUMN_NAME
            FROM INFORMATION_SCHEMA.COLUMNS
            WHERE TABLE_SCHEMA = DATABASE()
            AND TABLE_NAME = 'orders'
            AND COLUMN_NAME = 'shipping_phone'
        ");

        $busGateExists = DB::select("
            SELECT COLUMN_NAME
            FROM INFORMATION_SCHEMA.COLUMNS
            WHERE TABLE_SCHEMA = DATABASE()
            AND TABLE_NAME = 'orders'
            AND COLUMN_NAME = 'bus_gate'
        ");

        // Only drop columns that exist
        if ($stripeSessionIdExists && !empty($stripeSessionIdExists) ||
            $shippingCityExists && !empty($shippingCityExists) ||
            $shippingPhoneExists && !empty($shippingPhoneExists) ||
            $busGateExists && !empty($busGateExists)) {
            Schema::table('orders', function (Blueprint $table) use ($stripeSessionIdExists, $shippingCityExists, $shippingPhoneExists, $busGateExists) {
                if ($stripeSessionIdExists && !empty($stripeSessionIdExists)) {
                    $table->dropColumn('stripe_session_id');
                }
                if ($shippingCityExists && !empty($shippingCityExists)) {
                    $table->dropColumn('shipping_city');
                }
                if ($shippingPhoneExists && !empty($shippingPhoneExists)) {
                    $table->dropColumn('shipping_phone');
                }
                if ($busGateExists && !empty($busGateExists)) {
                    $table->dropColumn('bus_gate');
                }
            });
        }
    }
};