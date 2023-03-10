<?php

namespace App\Core\Application\Query;

use App\Core\Domain\ValueObject\User\Settings\ReportFormat;
use App\Core\DomainNew\Service\SettingsServiceInterface;
use App\Shared\Application\Query\QueryHandlerInterface;
use App\Shared\Application\Query\QueryInterface;

class GetAvailableReportFormatsHandler implements QueryHandlerInterface
{
    private SettingsServiceInterface $settingsService;

    public function __construct(SettingsServiceInterface $settingsService)
    {
        $this->settingsService = $settingsService;
    }

    /**
     * @param QueryInterface $query
     * @return ReportFormat[]
     */
    public function handle(QueryInterface $query): array
    {
        return $this->settingsService->getReportFormats();
    }
}
