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
            // Do the backend request
            $response = $client->request(
                'POST',
                'http://mockbin.org/request',
                [
                    'body' => [
                        'success' => 'yes',
                    ],
                ]
            );
            $response2 = $client->request(
                'POST',
                'http://mockbin.org/status/400/Bad+Request',
                [
                    'body' => [
                        'success' => 'no',
                    ],
                ]
            );

            dump($response->toArray(false));
            dump($response2->toArray(false));
        }
    )
    ->run();