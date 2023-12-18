<?php

namespace Nord\Lumen\Elasticsearch\Pipelines\Payloads;

/**
 * Class ApplyMigrationPayload
 * @package Nord\Lumen\Elasticsearch\Pipelines\Payloads
 */
class ApplyMigrationPayload extends MigrationPayload
{

    /**
     * @var string
     */
    private $targetVersionFile;

    /**
     * @var int
     */
    private $batchSize;

    /**
     * @var bool
     */
    private $updateAllTypes;

    /**
     * @var int
     */
    private $numberOfReplicas;

    /**
     * ApplyMigrationPayload constructor.
     *
     * @param string $configurationPath
     * @param int    $batchSize
     * @param bool   $updateAllTypes
     */
    public function __construct($configurationPath, $batchSize, $updateAllTypes = false)
    {
        parent::__construct($configurationPath);

        $this->batchSize      = $batchSize;
        $this->updateAllTypes = $updateAllTypes;
    }

    /**
     * @param string $targetVersionFile
     */
    public function setTargetVersionFile($targetVersionFile): void
    {
        $this->targetVersionFile = $targetVersionFile;
    }

    /**
     * @return string
     */
    public function getTargetVersionPath(): string
    {
        return sprintf('%s/%s', $this->getIndexVersionsPath(), $this->targetVersionFile);
    }

    /**
     * @return array
     */
    public function getTargetConfiguration(): array
    {
        $config = include $this->getTargetVersionPath();

        if ($this->updateAllTypes) {
            $config['update_all_types'] = true;
        }

        return $config;
    }

    /**
     * @return string
     */
    public function getTargetVersionName(): string
    {
        return $this->getTargetConfiguration()['index'];
    }

    /**
     * @return int
     */
    public function getBatchSize(): int
    {
        return $this->batchSize;
    }

    /**
     * @return ?int
     */
    public function getNumberOfReplicas(): ?int
    {
        return $this->numberOfReplicas;
    }

    /**
     * @param int $numberOfReplicas
     */
    public function setNumberOfReplicas($numberOfReplicas): void
    {
        $this->numberOfReplicas = $numberOfReplicas;
    }
}
