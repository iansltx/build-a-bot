<?php

namespace BuildABot\App;

use iansltx\DialogflowBridge\Answer;
use iansltx\DialogflowBridge\HandlerInterface;
use iansltx\DialogflowBridge\Question;
use iansltx\JoindInClient\Client;
use iansltx\JoindInClient\NoMoreEventsException;

class ScheduleHandler implements HandlerInterface
{
    protected $scheduleClient;

    public function __construct(Client $scheduleClient)
    {
        $this->scheduleClient = $scheduleClient;
    }

    public function __invoke(Question $question, Answer $answer): Answer
    {
        date_default_timezone_set('America/New_York');

        $nextTalkIndex = 0;
        // if data param is set, use it
        if ($dateParam = $question->getParam('date')) {
            $after = ($dateParam === date('Y-m-d') ?
                new \DateTimeImmutable() :
                \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $dateParam . ' 00:00:00'));
        // if context data is set, use it
        } elseif (($contextDateParam = $question->getContextParam('schedule-followup', 'previous-date')) &&
            ($after = \DateTimeImmutable::createFromFormat(\DateTime::ATOM, $contextDateParam))) {
            $nextTalkIndex = $question->getContextParam('schedule-followup', 'talk-index') ?: 1;
        } else {
            $after = new \DateTimeImmutable();
        }

        $filteredSchedule = $this->scheduleClient->getScheduleByEventId(6476)->filterOutBefore($after);

        try {
            $speech = 'The next event is ' . $filteredSchedule[$nextTalkIndex] . '.';

            // if we have multiple events left in the conference, let the user ask for the next one
            if (count($filteredSchedule) > 1) {
                $speech .= ' Would you like me to give you another event?';

                // If the talk we're on is in a time slot after the specified time, reset the talk index counter,
                // otherwise assume we're iterating through tracks at the same time and increment the talk index.
                $answer = $answer->withContext('schedule-followup', [
                    'previous-date' => $filteredSchedule[$nextTalkIndex]->getStartsAt()->format(\DateTime::ATOM),
                    'talk-index' => $filteredSchedule[$nextTalkIndex]->getStartsAt() != $after ? 1 : ++$nextTalkIndex
                ], 1);
            }

            return $answer->withSpeechAndText($speech);
        } catch (NoMoreEventsException $e) {
            return $answer->withSpeechAndText("There are no PHP World events on or after the " .
                $after->format('jS') . '.');
        }
    }
}
