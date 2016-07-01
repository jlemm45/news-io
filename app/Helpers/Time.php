<?php

namespace App\Helpers;


class Time {

    static function utcToCentral($utcDate) {
        $utcDate = new \DateTime($utcDate);
        $utcDate->setTimeZone(new \DateTimeZone('America/Chicago'));
        return $utcDate->format("Y-m-d h:i:s");
    }

    static function timePassed($date) {
        $articleDate = new \DateTime($date);
        $currentDate = new \DateTime("now");

        $interval = date_diff($articleDate, $currentDate);
        return $interval->format('%d:%h:%i:%s');
    }

}