<?php namespace Nord\Lumen\Elasticsearch\Documents\Bulk;

use Illuminate\Support\Arr;

class BulkResponseAggregator
{

    /**
     * @var array
     */
    private $errors = [];

    /**
     * @param array $response
     *
     * @return $this
     */
    public function addResponse(array $response): self
    {
        $this->parseErrors($response);

        return $this;
    }

    /**
     * @return bool
     */
    public function hasErrors(): bool
    {
        return count($this->errors) > 0;
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @return $this
     */
    public function reset(): self
    {
        $this->errors = [];

        return $this;
    }

    /**
     * @param array $response
     */
    protected function parseErrors(array $response): void
    {
        $items = $response['items'] ?? [];

        foreach ($items as $item) {
            $operation = Arr::first($item);

            // Ignore items without errors
            $error = $operation['error'] ?? null;

            if ($error === null) {
                continue;
            }

            // Ignore errors without caused_by
            $causedBy = $error['caused_by'] ?? null;

            if ($causedBy === null) {
                continue;
            }

            $this->errors[] = sprintf('Error "%s" reason "%s". Cause "%s" reason "%s". Index "%s", id "%s"',
                $error['type'], $error['reason'], $causedBy['type'], $causedBy['reason'], $operation['_index'],
                $operation['_id']);
        }
    }
}
