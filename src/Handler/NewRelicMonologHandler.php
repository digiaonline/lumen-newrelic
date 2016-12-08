<?php

namespace Nord\Lumen\NewRelic\Handler;

use Monolog\Handler\AbstractHandler;

/**
 * Class NewRelicMonologHandler
 * @package Nord\Lumen\NewRelic\Handler
 */
class NewRelicMonologHandler extends AbstractHandler
{

    /**
     * @inheritdoc
     */
    public function handle(array $record)
    {
        if (isset($record['context']['exception']) && $record['context']['exception'] instanceof \Exception) {
            newrelic_notice_error($record['message'], $record['context']['exception']);
        } else {
            newrelic_notice_error($record['message']);
        }
    }

}
