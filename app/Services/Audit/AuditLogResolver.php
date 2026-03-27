<?php

namespace App\Services\Audit;

use App\Models\CompanyProfile;
use App\Models\SystemMessage;
use App\Models\UiConfiguration;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AuditLogResolver
{
    /**
     * Register all auditable types here.
     */
    protected array $modelMap = [
        'company' => CompanyProfile::class,
        'systemmessage' => SystemMessage::class,
        'uiconfiguration' => UiConfiguration::class,
    ];

    public function resolve(string $type, string|int $id): Model
    {
        $type = strtolower(trim($type));

        $modelClass = $this->modelMap[$type] ?? null;

        if (!$modelClass || !class_exists($modelClass)) {
            throw new NotFoundHttpException("Audit model type [{$type}] is not supported.");
        }

        $model = $modelClass::query()->find($id);

        if (!$model) {
            throw new NotFoundHttpException("Record not found for audit type [{$type}] with ID [{$id}].");
        }

        if (!method_exists($model, 'audits')) {
            throw new NotFoundHttpException("Model [{$modelClass}] does not support audits.");
        }

        return $model;
    }
}
