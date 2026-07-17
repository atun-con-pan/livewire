<?php

use App\Models\FilesProject;
use App\Models\Project;

it('matches project files by category ignoring case and accents', function () {
    $project = new Project();

    $project->setRelation('files', collect([
        new FilesProject(['file_name' => 'Contrato Administrativo No. 01-2026']),
        new FilesProject(['file_name' => 'ACTA DE INICIO - 2026']),
        new FilesProject(['file_name' => 'Acta de Recepción final']),
    ]));

    expect($project->getFileNameForCategory('contrato'))->toBe('Contrato Administrativo No. 01-2026')
        ->and($project->getFileNameForCategory('inicio'))->toBe('ACTA DE INICIO - 2026')
        ->and($project->getFileNameForCategory('recepcion'))->toBe('Acta de Recepción final');
});
