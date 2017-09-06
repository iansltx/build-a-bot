<?php

namespace BuildABot\App;

use iansltx\ApiAiBridge\Answer;
use iansltx\ApiAiBridge\HandlerInterface;
use iansltx\ApiAiBridge\Question;
use iansltx\PNWPHP2017ScheduleClient\Client;
use iansltx\PNWPHP2017ScheduleClient\NoMoreEventsException;

class ScheduleHandler implements HandlerInterface
{
    protected $scheduleClient;

    public function __construct(Client $scheduleClient)
    {
        $this->scheduleClient = $scheduleClient;
    }

    public function __invoke(Question $question, Answer $answer): Answer
    {
        date_default_timezone_set('America/Los_Angeles');
        $dateParam = $question->getParam('date', date('Y-m-d'));

        $filteredSchedule = $this->scheduleClient->getSchedule()->filterOutPast(
            $after = ($dateParam === date('Y-m-d') ?
                new \DateTimeImmutable() :
                \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $dateParam . ' 00:00:00'))
        );

        try {
            return $answer->withSpeechAndText('The next event is ' . $filteredSchedule->first() . '.');
        } catch (NoMoreEventsException $e) {
            return $answer->withSpeechAndText("There are no Pacific Northwest PHP events on or after the " .
                $after->format('jS') . '.');
        }
    }
}
