<?php

use App\Livewire\Explorer\Index;
use App\Models\File;
use App\Models\Folder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
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

test('a file can be moved to another folder from the explorer', function () {
    $parent = Folder::create(['name' => 'parent', 'parent_id' => null]);
    $destination = Folder::create(['name' => 'destination', 'parent_id' => null]);

    $file = File::create([
        'folder_id' => $parent->id,
        'name' => 'moved.txt',
        'physical_name' => 'moved.txt',
        'extension' => 'txt',
        'mime_type' => 'text/plain',
        'size' => 9,
    ]);

    Livewire::test(Index::class)
        ->call('moveItem', 'file', $file->id, $destination->id);

    $file->refresh();

    expect($file->folder_id)->toBe($destination->id);
});

test('a folder can be moved to another folder from the explorer', function () {
    $parent = Folder::create(['name' => 'parent', 'parent_id' => null]);
    $destination = Folder::create(['name' => 'destination', 'parent_id' => null]);

    $child = Folder::create(['name' => 'child', 'parent_id' => $parent->id]);

    Livewire::test(Index::class)
        ->call('moveItem', 'folder', $child->id, $destination->id);

    $child->refresh();

    expect($child->parent_id)->toBe($destination->id);
});

test('a folder cannot be moved into one of its descendants', function () {
    $parent = Folder::create(['name' => 'parent', 'parent_id' => null]);
    $child = Folder::create(['name' => 'child', 'parent_id' => $parent->id]);

    Livewire::test(Index::class)
        ->call('moveItem', 'folder', $parent->id, $child->id)
        ->assertHasErrors(['targetFolderId']);

    $parent->refresh();

    expect($parent->parent_id)->toBeNull();
});

test('multiple items can be moved to another folder from the explorer', function () {
    $parent = Folder::create(['name' => 'parent', 'parent_id' => null]);
    $destination = Folder::create(['name' => 'destination', 'parent_id' => null]);
    $folder = Folder::create(['name' => 'child', 'parent_id' => $parent->id]);

    $file = File::create([
        'folder_id' => $parent->id,
        'name' => 'multi.txt',
        'physical_name' => 'multi.txt',
        'extension' => 'txt',
        'mime_type' => 'text/plain',
        'size' => 9,
    ]);

    Livewire::test(Index::class)
        ->call('moveItems', [
            ['type' => 'folder', 'id' => $folder->id],
            ['type' => 'file', 'id' => $file->id],
        ], $destination->id);

    $folder->refresh();
    $file->refresh();

    expect($folder->parent_id)->toBe($destination->id);
    expect($file->folder_id)->toBe($destination->id);
});

test('moving items keeps the user in the current folder instead of opening the destination folder', function () {
    $parent = Folder::create(['name' => 'parent', 'parent_id' => null]);
    $destination = Folder::create(['name' => 'Documentos', 'parent_id' => $parent->id]);
    $file = File::create([
        'folder_id' => $parent->id,
        'name' => 'move-me.txt',
        'physical_name' => 'move-me.txt',
        'extension' => 'txt',
        'mime_type' => 'text/plain',
        'size' => 7,
    ]);

    Livewire::test(Index::class, ['folder' => $parent])
        ->call('moveItem', 'file', $file->id, $destination->id)
        ->assertRedirect(route('folder.show', ['folder' => $parent->id], absolute: false));

    $file->refresh();
    expect($file->folder_id)->toBe($destination->id);
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

test('a complete folder tree can be uploaded with all nested folders and files', function () {
    Storage::fake('public');

    $rootFolder = Folder::create(['name' => 'root', 'parent_id' => null]);

    $fileA = UploadedFile::fake()->create('report.txt', 10);
    $fileB = UploadedFile::fake()->create('other.txt', 10);

    Livewire::test(Index::class, ['folder' => $rootFolder])
        ->set('uploadedFolderFiles', [$fileA, $fileB])
        ->set('folderUploadPayload', [
            ['relativePath' => 'root-folder/sub-folder/report.txt'],
            ['relativePath' => 'root-folder/other.txt'],
        ])
        ->call('storeFolderUpload');

    $parentFolder = Folder::where('parent_id', $rootFolder->id)->whereRaw('LOWER(name) = ?', ['root-folder'])->first();
    $subFolder = Folder::where('parent_id', $parentFolder->id)->whereRaw('LOWER(name) = ?', ['sub-folder'])->first();

    expect($parentFolder)->not->toBeNull();
    expect($subFolder)->not->toBeNull();
    expect(File::where('folder_id', $subFolder->id)->whereRaw('LOWER(name) = ?', ['report.txt'])->exists())->toBeTrue();
    expect(File::where('folder_id', $parentFolder->id)->whereRaw('LOWER(name) = ?', ['other.txt'])->exists())->toBeTrue();
});
