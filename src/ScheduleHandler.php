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

        // if context date is set, use it
        if (($contextDateParam = $question->getContextParam('schedule-followup', 'previous-date')) &&
                ($contextDate = \DateTimeImmutable::createFromFormat(\DateTime::ATOM, $contextDateParam))) {
            $filteredSchedule = $this->scheduleClient->getSchedule()->filterOutPast($contextDate);
            return $answer->withSpeechAndText('Okay. The next event is ' . $filteredSchedule->second() . '.');
        }

        $dateParam = $question->getParam('date', date('Y-m-d'));

        $filteredSchedule = $this->scheduleClient->getSchedule()->filterOutPast(
            $after = ($dateParam === date('Y-m-d') ?
                new \DateTimeImmutable() :
                \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $dateParam . ' 00:00:00'))
        );

        try {
            $speech = 'The next event is ' . $filteredSchedule->first() . '.';

            // if we have multiple events left in the conference, let the user ask for the next one
            if (count($filteredSchedule) > 1) {
                $speech .= ' Would you like me to tell you about the event after that one?';
                $answer = $answer->withContext('schedule-followup', [
                    'previous-date' => $filteredSchedule->first()->getStartsAt()->format(\DateTime::ATOM)
                ], 1);
            }

            return $answer->withSpeechAndText($speech);
        } catch (NoMoreEventsException $e) {
            return $answer->withSpeechAndText("There are no Pacific Northwest PHP events on or after the " .
                $after->format('jS') . '.');
        }
    }
}
