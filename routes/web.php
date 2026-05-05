<?php

use App\Livewire\Collaborators\Index as CollaboratorIndex;
use App\Livewire\Collaborators\Create as CollaboratorCreate;
use App\Livewire\Collaborators\Edit as CollaboratorEdit;
use App\Livewire\Collaborators\Show as CollaboratorShow;
use App\Livewire\Collaborators\File as CollaboratorFile;

use App\Livewire\Documents\Index as DocumentsIndex;
use App\Livewire\Documents\Create as DocumentsCreate;
use App\Livewire\Documents\Show as DocumentsShow;
use App\Livewire\Documents\Edit as DocumentsEdit;

use App\Livewire\Projects\Index as ProjectIndex;
use App\Livewire\Projects\Create as ProjectCreate;
use App\Livewire\Projects\Show as ProjectShow;
use App\Livewire\Projects\Edit as ProjectEdit;

use App\Livewire\Contracts\Index as ContractIndex;
use App\Livewire\Contracts\Create as ContractCreate;
use App\Livewire\Contracts\Show as ContractShow;
use App\Livewire\Contracts\Edit as ContractEdit;

use App\Livewire\Audit\Index as AuditIndex;
use App\Livewire\Audit\Show as AuditShow;

use App\Livewire\Users\Index as UsersIndex;
use App\Livewire\Users\Create as UsersCreate;
use App\Livewire\Users\Show as UsersShow;
use App\Livewire\Users\Edit as UsersEdit;

use App\Livewire\Affiliates\Index as AffilatesIndex;
use App\Livewire\Affiliates\Create as AffilatesCreate;


use Illuminate\Support\Facades\Route;

Route::view('/', 'livewire.auth.login')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard')->middleware('role:admin,root,user');

    Route::get('collaborators', CollaboratorIndex::class)->name('collaborators.index')->middleware('role:admin,root,user');
    Route::get('collaborators/create', CollaboratorCreate::class)->name('collaborators.create')->middleware('role:admin,root,user');
    Route::get('collaborators/edit/{collaborator}', CollaboratorEdit::class)->name('collaborators.edit')->middleware('role:admin,root');
    Route::get('collaborators/show/{collaborator}', CollaboratorShow::class)->name('collaborators.show')->middleware('role:admin,root,user');
    Route::get('collaborators/{collaborator}/files', CollaboratorFile::class)->name('collaborators.file')->middleware('role:admin,root,user');

    Route::get('documents', DocumentsIndex::class)->name('documents.index')->middleware('role:admin,root,user');
    Route::get('documents/create', DocumentsCreate::class)->name('documents.create')->middleware('role:admin,root,user');
    Route::get('documents/show/{document}', DocumentsShow::class)->name('documents.show')->middleware('role:admin,root,user');
    Route::get('documents/edit/{document}', DocumentsEdit::class)->name('documents.edit')->middleware('role:admin,root');

    Route::get('projects', ProjectIndex::class)->name('projects.index')->middleware('role:admin,root,user');
    Route::get('projects/create', ProjectCreate::class)->name('projects.create')->middleware('role:admin,root,user');
    Route::get('projects/show/{project}', ProjectShow::class)->name('projects.show')->middleware('role:admin,root,user');
    Route::get('projects/edit/{project}', ProjectEdit::class)->name('projects.edit')->middleware('role:admin,root');

    Route::get('contracts', ContractIndex::class)->name('contracts.index')->middleware('role:admin,root,user');
    Route::get('contracts/create', ContractCreate::class)->name('contracts.create')->middleware('role:admin,root,user');
    Route::get('contracts/show/{contract}', ContractShow::class)->name('contracts.show')->middleware('role:admin,root,user');
    Route::get('contracts/edit/{contract}', ContractEdit::class)->name('contracts.edit')->middleware('role:admin,root');

    Route::get('audit', AuditIndex::class)->name('audit.index')->middleware('role:root');
    Route::get('audit/show/{audit}', AuditShow::class)->name('audit.show')->middleware('role:root');

    Route::get('users', UsersIndex::class)->name('users.index')->middleware('role:root');
    Route::get('users/create', UsersCreate::class)->name('users.create')->middleware('role:root');
    Route::get('users/show/{user}', UsersShow::class)->name('users.show')->middleware('role:root');
    Route::get('users/edit/{user}', UsersEdit::class)->name('users.edit')->middleware('role:root');

    Route::get('affiliates', AffilatesIndex::class)->name('affiliates.index')->middleware('role:root');
    Route::get('affiliates/create', AffilatesCreate::class)->name('affiliates.create')->middleware('role:root');
});

require __DIR__.'/settings.php';
