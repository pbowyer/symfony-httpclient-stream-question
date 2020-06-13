<?php

require __DIR__.'/vendor/autoload.php';

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\SingleCommandApplication;
use Symfony\Component\HttpClient\HttpClient;

(new SingleCommandApplication())
    ->setCode(
        function (InputInterface $input, OutputInterface $output) {
            $client = HttpClient::create();

            $responses = [];
            // Do the backend request
            $responses[] = $client->request(
                'POST',
                'http://mockbin.org/request',
                [
                    'body' => [
                        'success' => 'yes',
                    ],
                ]
            );
            $responses[] = $client->request(
                'POST',
                'http://mockbin.org/status/400/Bad+Request',
                [
                    'body' => [
                        'success' => 'no',
                    ],
                ]
            );
            foreach ($client->stream($responses) as $response => $chunk) {
                if ($chunk->isFirst()) {
                    //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
                    // @HELP If I uncomment these lines, it works.
                    // If I don't, an exception is thrown.
//                    if ($response->getStatusCode() != 200) {
//
//                    }
                    //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
                } elseif ($chunk->isLast()) {
                    $json = $response->toArray(false);
                    dump($json);
                }
            }
        }
    )
    ->run();