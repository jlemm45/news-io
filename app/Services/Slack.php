<?php
/**
 * Created by PhpStorm.
 * User: Jaden Lemmon
 * Date: 5/2/16
 * Time: 3:25 PM
 *
 * Used to integrate with slack rooms
 */

namespace App\Services;

class Slack {

    var $serviceID = '';

    function __construct($serviceID = false) {
        $this->serviceID = !$serviceID ? 'T27T9SS4T/B2GA87HHD/Qu3Q87wKpK9qBaHONkzbnFBO' : $serviceID; //default to notifications room
    }

    /**
     * @param $header
     * @param $title
     * @param $value
     *
     * Send's a message to a room
     */
    function send($header,$title,$value) {
        $payload = '{"attachments":[{"fallback":"'.$header.'","pretext":"'.$header.'","color":"#2ab27b","fields":[{"title":"'.$title.'","value":"'.$value.'","short":false}]}]}';
        $curl = \curl_init();
        \curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => 'https://hooks.slack.com/services/'.$this->serviceID,
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_HTTPHEADER => array('Accept: application/json','Content-Type: application/json')
        ));
        \curl_exec($curl);
        \curl_close($curl);
    }
}