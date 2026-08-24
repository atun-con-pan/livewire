<?php

use App\Livewire\Explorer\Index;
use App\Models\File;
use App\Models\Folder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('a folder can be renamed from the explorer', function () {
    $folder = Folder::create([
        'name' => 'Carpeta vieja',
        'parent_id' => null,
    ]);

    Livewire::test(Index::class)
        ->set('renameType', 'folder')
        ->set('renameId', $folder->id)
        ->set('renameName', 'Carpeta nueva')
        ->call('renameItem');

    $folder->refresh();

    expect($folder->name)->toBe('Carpeta nueva');
});

test('a file can be deleted from the database and from disk', function () {
    Storage::fake('public');

    Storage::disk('public')->put('files/test-file.txt', 'contenido');

    $file = File::create([
        'folder_id' => null,
        'name' => 'test-file.txt',
        'physical_name' => 'test-file.txt',
        'extension' => 'txt',
        'mime_type' => 'text/plain',
        'size' => 9,
    ]);

    Livewire::test(Index::class)
        ->call('deleteItem', 'file', $file->id);

    expect(File::find($file->id))->toBeNull();
    expect(Storage::disk('public')->exists('files/test-file.txt'))->toBeFalse();
});

test('a folder and all nested children are deleted from the database and from disk', function () {
    Storage::fake('public');

    $parent = Folder::create(['name' => 'parent', 'parent_id' => null]);
    $child = Folder::create(['name' => 'child', 'parent_id' => $parent->id]);

    Storage::disk('public')->put('files/root-file.txt', 'root');
    Storage::disk('public')->put('files/nested-file.txt', 'nested');

    $rootFile = File::create([
        'folder_id' => $parent->id,
        'name' => 'root-file.txt',
        'physical_name' => 'root-file.txt',
        'extension' => 'txt',
        'mime_type' => 'text/plain',
        'size' => 4,
    ]);

    $nestedFile = File::create([
        'folder_id' => $child->id,
        'name' => 'nested-file.txt',
        'physical_name' => 'nested-file.txt',
        'extension' => 'txt',
        'mime_type' => 'text/plain',
        'size' => 6,
    ]);

    Livewire::test(Index::class)
        ->call('deleteItem', 'folder', $parent->id);

    expect(Folder::find($parent->id))->toBeNull();
    expect(Folder::find($child->id))->toBeNull();
    expect(File::find($rootFile->id))->toBeNull();
    expect(File::find($nestedFile->id))->toBeNull();
    expect(Storage::disk('public')->exists('files/root-file.txt'))->toBeFalse();
    expect(Storage::disk('public')->exists('files/nested-file.txt'))->toBeFalse();
});
