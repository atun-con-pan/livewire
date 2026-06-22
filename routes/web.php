<?php

use App\Livewire\Explorer\Index as FileExplorer;

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
use App\Livewire\Projects\File as ProjectFile;
use App\Livewire\Projects\Ofices as ProjectOfices;

use App\Livewire\Contracts\Index as ContractIndex;
use App\Livewire\Contracts\Create as ContractCreate;
use App\Livewire\Contracts\Show as ContractShow;
use App\Livewire\Contracts\Edit as ContractEdit;
use App\Livewire\Contracts\File as ContractFile;

use App\Livewire\Audit\Index as AuditIndex;
use App\Livewire\Audit\Show as AuditShow;

use App\Livewire\Users\Index as UsersIndex;
use App\Livewire\Users\Create as UsersCreate;
use App\Livewire\Users\Show as UsersShow;
use App\Livewire\Users\Edit as UsersEdit;

use App\Livewire\Affiliates\Index as AffilatesIndex;
use App\Livewire\Affiliates\Create as AffilatesCreate;
use App\Livewire\Affiliates\Edit as AffilatesEdit;
use App\Livewire\Affiliates\Show as AffilatesShow;

use App\Livewire\AffiliatePeriods\Index as AffiliatePeriodsIndex;
use App\Livewire\AffiliatePeriods\Create as AffiliatePeriodsCreate;
use App\Livewire\AffiliatePeriods\Edit as AffiliatePeriodsEdit;


use Illuminate\Support\Facades\Route;

Route::view('/', 'livewire.auth.login')->name('home');

Route::middleware(['auth', 'verified', 'throttle:50,1', 'role:admin,root,user'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::view('authenticates', 'dashboard.authenticates')->name('authenticates');
    Route::view('affidavits', 'dashboard.affidavits')->name('affidavits');
    Route::view('reports', 'dashboard.reports')->name('reports');
    Route::view('ilovepdf', 'dashboard.ilovepdf')->name('ilovepdf');
    Route::view('invoices', 'dashboard.invoices')->name('invoices');

    Route::get('collaborators', CollaboratorIndex::class)->name('collaborators.index');
    Route::get('collaborators/create', CollaboratorCreate::class)->name('collaborators.create');
    Route::get('collaborators/show/{collaborator}', CollaboratorShow::class)->name('collaborators.show');
    Route::get('collaborators/{collaborator}/files', CollaboratorFile::class)->name('collaborators.file');

    Route::get('documents', DocumentsIndex::class)->name('documents.index');
    Route::get('documents/create', DocumentsCreate::class)->name('documents.create');
    Route::get('documents/show/{document:file_name}', DocumentsShow::class)->name('documents.show');

    Route::get('projects', ProjectIndex::class)->name('projects.index');
    Route::get('projects/create', ProjectCreate::class)->name('projects.create');
    Route::get('projects/show/{project}', ProjectShow::class)->name('projects.show');
    Route::get('projects/{project}/files', ProjectFile::class)->name('projects.file');
    Route::get('projects/{project}/ofices', ProjectOfices::class)->name('projects.ofices');

    Route::get('contracts', ContractIndex::class)->name('contracts.index');
    Route::get('contracts/create', ContractCreate::class)->name('contracts.create');
    Route::get('contracts/show/{contract}', ContractShow::class)->name('contracts.show');
    Route::get('contracts/{contract}/files', ContractFile::class)->name('contracts.file');

    Route::get('affiliates', AffilatesIndex::class)->name('affiliates.index');
    Route::get('affiliates/create', AffilatesCreate::class)->name('affiliates.create');
    Route::get('affiliates/edit/{affiliate}', AffilatesEdit::class)->name('affiliates.edit');
    Route::get('affiliates/show/{affiliate}', AffilatesShow::class)->name('affiliates.show');
});

Route::middleware(['auth', 'verified', 'throttle:50,1', 'role:admin,root'])->group(function () {
    Route::get('collaborators/edit/{collaborator}', CollaboratorEdit::class)->name('collaborators.edit');

    Route::get('documents/edit/{document}', DocumentsEdit::class)->name('documents.edit');

    Route::get('projects/edit/{project}', ProjectEdit::class)->name('projects.edit');
    
    Route::get('contracts/edit/{contract}', ContractEdit::class)->name('contracts.edit');

    Route::get('periods', AffiliatePeriodsIndex::class)->name('periods.index');
    Route::get('periods/create', AffiliatePeriodsCreate::class)->name('periods.create');
    Route::get('periods/edit/{period}', AffiliatePeriodsEdit::class)->name('periods.edit');
});

Route::middleware(['auth', 'verified', 'throttle:50,1', 'role:root'])->group(function () {
    Route::get('audit', AuditIndex::class)->name('audit.index');
    Route::get('audit/show/{audit}', AuditShow::class)->name('audit.show');

    Route::get('users', UsersIndex::class)->name('users.index');
    Route::get('users/create', UsersCreate::class)->name('users.create');
    Route::get('users/show/{user:name}', UsersShow::class)->name('users.show');
    Route::get('users/edit/{user}', UsersEdit::class)->name('users.edit');
});

require __DIR__.'/settings.php';
