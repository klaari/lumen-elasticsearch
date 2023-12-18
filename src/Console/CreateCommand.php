<?php namespace Nord\Lumen\Elasticsearch\Console;

class CreateCommand extends AbstractCommand
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'elastic:index:create {config : Configuration file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates an Elasticsearch index from a configuration file.';

    /**
     * @inheritdoc
     */
    public function handle(): int
    {
        $config = (string)$this->argument('config');

        $filePath = (string)realpath($config);

        if (!file_exists($filePath)) {
            $this->error(sprintf("Configuration file '%s' does not exist.", $config));

            return 1;
        }

        $params = require($filePath);
        $params = $this->elasticsearchService->getPrefixedIndexParameters($params);

        $this->info(sprintf("Creating index '%s' ...", $params['index']));

        $this->elasticsearchService->indices()->create($params);

        $this->info(sprintf("Index '%s' created.", $params['index']));

        return 0;
    }
}
