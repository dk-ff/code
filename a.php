<?php

class Main
{
    const INCH_TO_CM = 2.54;
    const LB_TO_KG = 0.454;
    const VOLUME_BASE = 250;

    public function test(float $length, float $width, float $height, float $weight): array
    {
        // Convert dimensions from cm to inches and weight from kg to lb
        $lengthIn = ceil($length / self::INCH_TO_CM);
        $widthIn = ceil($width / self::INCH_TO_CM);
        $heightIn = ceil($height / self::INCH_TO_CM);
        $weightLb = ceil($weight / self::LB_TO_KG);

        // Determine the longest, middle, and shortest sides
        $sides = [$lengthIn, $widthIn, $heightIn];
        sort($sides);
        list($shortest, $middle, $longest) = $sides;

        // Calculate girth and volume weight
        $girth = $longest + ($middle + $shortest) * 2;
        $volumeWeight = ceil(($longest * $middle * $shortest) / self::VOLUME_BASE);

        // Calculate actual weight
        $actualWeight = max($weightLb, $volumeWeight);

        // Determine package type
        if (
            $actualWeight > 150 ||
            $longest > 108 ||
            $girth > 165
        ) {
            return ['OUT_SPACE'];
        } elseif (
            ($girth > 130 && $girth <= 165) ||
            ($longest >= 96 && $longest < 108)
        ) {
            return ['OVERSIZE'];
        }

        $ahsTypes = [];
        if ($actualWeight > 50 && $actualWeight <= 150) {
            $ahsTypes[] = 'AHS-WEIGHT';
        }
        if (
            $girth > 105 ||
            ($longest >= 48 && $longest < 108) ||
            $middle >= 30
        ) {
            $ahsTypes[] = 'AHS-SIZE';
        }

        return $ahsTypes;
    }
}

$obj = new Main();
var_dump($obj->test(68, 70, 60, 23)); // Output: array(2) { [0]=> string(11) "AHS-WEIGHT" [1]=> string(8) "AHS-SIZE" }
var_dump($obj->test(114.50, 42, 26, 47.5)); // Output: array(1) { [0]=> string(11) "AHS-WEIGHT" }
var_dump($obj->test(162, 60, 11, 14)); // Output: array(1) { [0]=> string(8) "AHS-SIZE" }
var_dump($obj->test(113, 64, 42.5, 35.85)); // Output: array(1) { [0]=> string(8) "OVERSIZE"
var_dump($obj->test(114.5, 17, 51.5, 16.5)); //输入[114.5, 17, 51.5, 16.5], 输出[]
