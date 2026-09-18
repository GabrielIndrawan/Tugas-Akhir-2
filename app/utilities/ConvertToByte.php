<?php

function convertRawValueFromByte($size): string {

    if($size === -3) {
        return "Unlimited";
    }

    $unit = ["B", "KB", "MB", "GB", "TB"];
    $unitIndex = 0;

    while ($size >= 1024 && $unitIndex < count($unit) - 1) {
        $size /= 1024;
        $unitIndex++;
    }

    return round($size, 2) . " " . $unit[$unitIndex];
}

?>