<?php

namespace App\Models;

use App\Models\AffiliatePeriod;
use App\Models\Contract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableTrait;
use OwenIt\Auditing\Contracts\Auditable;

class Project extends Model implements Auditable
{
    use HasFactory, AuditableTrait;

    protected $fillable = ['nog', 'event', 'name', 'url', 'client', 'presentation_date', 'start_date', 'end_date', 'price', 'status'];

    protected $casts = [
        'presentation_date' => 'date',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function files()
    {
        return $this->hasMany(FilesProject::class);
    }

    public function ofices()
    {
        return $this->hasMany(Ofices::class);
    }

    public function periods()
    {
        return $this->hasMany(AffiliatePeriod::class);
    }

    public function affiliates()
    {
        return $this->belongsToMany(Affiliate::class, 'affiliate_periods')
            ->withPivot(['start_date', 'end_date'])
            ->withTimestamps();
    }

    public function contract()
    {
        return $this->hasOne(Contract::class);
    }

    public function getFileNameForCategory(string $category): ?string
    {
        $file = $this->getFileForCategory($category);

        return $file?->file_name;
    }

    public function getFilePathForCategory(string $category): ?string
    {
        $file = $this->getFileForCategory($category);

        return $file?->file_path;
    }

    protected function getFileForCategory(string $category): ?FilesProject
    {
        $files = $this->relationLoaded('files') ? $this->files : $this->files()->get();

        foreach ($files as $file) {
            if ($this->matchesCategory($file->file_name ?? '', $category)) {
                return $file;
            }
        }

        return null;
    }

    protected function matchesCategory(string $fileName, string $category): bool
    {
        $normalizedName = $this->normalizeText($fileName);
        $keywords = match ($category) {
            'contrato' => ['contrato'],
            'inicio' => ['acta de inicio', 'acta inicio', 'inicio'],
            'recepcion' => ['acta de recepcion', 'acta recepcion', 'recepcion', 'recepcion'],
            default => [],
        };

        foreach ($keywords as $keyword) {
            if (str_contains($normalizedName, $keyword)) {
                return true;
            }
        }

        return false;
    }

    protected function normalizeText(string $value): string
    {
        $value = mb_strtolower($value, 'UTF-8');
        $value = strtr($value, [
            'á' => 'a', 'à' => 'a', 'ä' => 'a', 'â' => 'a',
            'é' => 'e', 'è' => 'e', 'ë' => 'e', 'ê' => 'e',
            'í' => 'i', 'ì' => 'i', 'ï' => 'i', 'î' => 'i',
            'ó' => 'o', 'ò' => 'o', 'ö' => 'o', 'ô' => 'o',
            'ú' => 'u', 'ù' => 'u', 'ü' => 'u', 'û' => 'u',
            'ñ' => 'n', 'ç' => 'c',
        ]);

        return trim(preg_replace('/\s+/', ' ', $value) ?? $value);
    }
}
