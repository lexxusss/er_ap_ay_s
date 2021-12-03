<?php

/**
 * Task v1
For numbers from 1 to 20:

Print that number
If number divides by 3, print pa instead
If number divides by 5, print pow instead
If number divides by both 3 and 5, print papow instead
Put spaces in between of the elements (but not before first or after the last one).

Expected output:

1 2 pa 4 pow pa 7 8 pa pow 11 pa 13 14 papow 16 17 pa 19 pow
 */

$res = [];
for ($i = 1; $i < 21; $i++) {
    if (0 === $i % 3 && 0 === $i % 5) {
        $res[] = 'papow';
    } elseif (0 === $i % 3) {
        $res[] = 'pa';
    } elseif (0 === $i % 5) {
        $res[] = 'pow';
    } else {
        $res[] = $i;
    }
}

echo join(' ', $res) . "\r\n";


