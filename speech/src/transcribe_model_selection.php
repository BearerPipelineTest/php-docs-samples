<?php
/**
 * Copyright 2018 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 * For instructions on how to run the full sample:
 *
 * @see https://github.com/GoogleCloudPlatform/php-docs-samples/tree/main/speech/README.md
 */

// Include Google Cloud dependendencies using Composer
require_once __DIR__ . '/../vendor/autoload.php';

if (count($argv) != 3) {
    return print("Usage: php transcribe_model_selection.php AUDIO_FILE MODEL\n");
}
list($_, $audioFile, $model) = $argv;

# [START speech_transcribe_model_selection]
use Google\Cloud\Speech\V1\SpeechClient;
use Google\Cloud\Speech\V1\RecognitionAudio;
use Google\Cloud\Speech\V1\RecognitionConfig;
use Google\Cloud\Speech\V1\RecognitionConfig\AudioEncoding;

/** Uncomment and populate these variables in your code */
// $audioFile = 'path to an audio file';
// $model = 'video';

// change these variables if necessary
$encoding = AudioEncoding::LINEAR16;
$sampleRateHertz = 32000;
$languageCode = 'en-US';

// get contents of a file into a string
$content = file_get_contents($audioFile);

// set string as audio content
$audio = (new RecognitionAudio())
    ->setContent($content);

// set config
$config = (new RecognitionConfig())
    ->setEncoding($encoding)
    ->setSampleRateHertz($sampleRateHertz)
    ->setLanguageCode($languageCode)
    ->setModel($model);

// create the speech client
$client = new SpeechClient();

// make the API call
$response = $client->recognize($config, $audio);
$results = $response->getResults();

// print results
foreach ($results as $result) {
    $alternatives = $result->getAlternatives();
    $mostLikely = $alternatives[0];
    $transcript = $mostLikely->getTranscript();
    $confidence = $mostLikely->getConfidence();
    printf('Transcript: %s' . PHP_EOL, $transcript);
    printf('Confidence: %s' . PHP_EOL, $confidence);
}

$client->close();
# [END speech_transcribe_model_selection]
