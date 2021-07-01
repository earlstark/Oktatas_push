<?php

require_once "vendor/autoload.php";

foreach (\app\ScoreCalculator::getInstance()->getScores() as $score) {
    $sc = $score->getScore();
    if ($sc["error"]) {
        echo $sc["error"]."<br>";
    } else {
        echo $sc["score"]." (".$sc["baseScore"]." + ".$sc["plusScore"].")<br>";
    }
}