<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            DB::statement('ALTER TABLE files RENAME TO files_old');

            DB::statement('CREATE TABLE files (
                id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
                folder_id INTEGER NULL,
                name VARCHAR NOT NULL,
                physical_name TEXT NOT NULL,
                extension VARCHAR NOT NULL,
                mime_type VARCHAR NOT NULL,
                size INTEGER NOT NULL DEFAULT 0,
                created_at DATETIME NULL,
                updated_at DATETIME NULL,
                FOREIGN KEY(folder_id) REFERENCES folders(id) ON DELETE CASCADE
            )');

            DB::statement('INSERT INTO files (id, folder_id, name, physical_name, extension, mime_type, size, created_at, updated_at)
                SELECT id, folder_id, name, physical_name, extension, mime_type, size, created_at, updated_at
                FROM files_old');

            DB::statement('DROP TABLE files_old');

            return;
        }

        Schema::table('files', function (Blueprint $table) {
            $table->unsignedBigInteger('folder_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            DB::statement('ALTER TABLE files RENAME TO files_old');

            DB::statement('CREATE TABLE files (
                id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
                folder_id INTEGER NOT NULL,
                name VARCHAR NOT NULL,
                physical_name TEXT NOT NULL,
                extension VARCHAR NOT NULL,
                mime_type VARCHAR NOT NULL,
                size INTEGER NOT NULL DEFAULT 0,
                created_at DATETIME NULL,
                updated_at DATETIME NULL,
                FOREIGN KEY(folder_id) REFERENCES folders(id) ON DELETE CASCADE
            )');

            DB::statement('INSERT INTO files (id, folder_id, name, physical_name, extension, mime_type, size, created_at, updated_at)
                SELECT id, folder_id, name, physical_name, extension, mime_type, size, created_at, updated_at
                FROM files_old');

            DB::statement('DROP TABLE files_old');

            return;
        }

        Schema::table('files', function (Blueprint $table) {
            $table->unsignedBigInteger('folder_id')->nullable(false)->change();
        });
    }
};
