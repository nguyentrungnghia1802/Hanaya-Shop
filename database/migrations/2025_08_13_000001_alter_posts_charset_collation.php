<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::statement("ALTER TABLE posts MODIFY title VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
        DB::statement("ALTER TABLE posts MODIFY slug VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
        DB::statement("ALTER TABLE posts MODIFY content TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
        DB::statement("ALTER TABLE posts MODIFY image VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
    }

    public function down(): void
    {
        // Nếu muốn revert, có thể đổi lại charset/collation cũ nếu cần
    }
};
