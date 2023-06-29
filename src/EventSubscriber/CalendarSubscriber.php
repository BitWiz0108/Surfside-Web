<?php

namespace App\EventSubscriber;

use App\Repository\CleanRepository;
use CalendarBundle\CalendarEvents;
use CalendarBundle\Entity\Event;
use CalendarBundle\Event\CalendarEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CalendarSubscriber implements EventSubscriberInterface
{
    public function __construct(private CleanRepository $cleanRepository) {}

    public static function getSubscribedEvents()
    {
        return [
            CalendarEvents::SET_DATA => 'onCalendarSetData',
        ];
    }

    public function onCalendarSetData(CalendarEvent $calendar)
    {
        $start = $calendar->getStart();
        $end = $calendar->getEnd();
        $filters = $calendar->getFilters();
        // Modify the query to fit to your entity and needs
        // Change booking.beginAt by your start date property
        $cleanappointments = $this->cleanRepository
            ->createQueryBuilder('c')
            ->where('c.scheduled BETWEEN :start and :end')
            ->setParameter('start', $start->format('Y-m-d H:i:s'))
            ->setParameter('end', $end->format('Y-m-d H:i:s'))
            ->getQuery()
            ->getResult()
        ;

        foreach ($cleanappointments as $clean) {
            // this create the events with your data (here booking data) to fill calendar
            $scheduledEvent = new Event(
                $clean->getProperty()->getTitle(),
                $clean->getScheduled(),
                $clean->getScheduled() // If the end date is null or not defined, an all day event is created.
            );
            /*
             * Add custom options to events
             *
             * For more information see: https://fullcalendar.io/docs/event-object
             * and: https://github.com/fullcalendar/fullcalendar/blob/master/src/core/options.ts
             */
            $scheduledEvent->setOptions([
                'backgroundColor' => 'blue',
                'borderColor' => 'blue',
                'url' => '/clean/'.$clean->getId(),
            ]);
            // finally, add the event to the CalendarEvent to fill the calendar
            $calendar->addEvent($scheduledEvent);
        }
    }
}