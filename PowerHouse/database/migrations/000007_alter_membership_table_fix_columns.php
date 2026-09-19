<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE `membership`
                MODIFY COLUMN `start_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                MODIFY COLUMN `end_date` DATETIME NULL,
                MODIFY COLUMN `status` ENUM('active', 'expired', 'cancelled', 'suspended') NOT NULL DEFAULT 'active',
                MODIFY COLUMN `payment_status` ENUM('paid', 'pending', 'failed', 'refunded') NOT NULL DEFAULT 'pending'
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE `membership`
                MODIFY COLUMN `start_date` TIME NOT NULL,
                MODIFY COLUMN `end_date` TIME NOT NULL,
                MODIFY COLUMN `status` ENUM('active', 'expired', 'cancelled', 'suspended') NOT NULL,
                MODIFY COLUMN `payment_status` ENUM('paid', 'pending', 'failed', 'refunded') NOT NULL
        ");
    }
};
