<?php
use Ramsey\Uuid\Uuid;


function save($file) {
    if ($file['size'] > 0) {
        $target_dir = '/var/www/media/uploads/';
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $target_file = $target_dir . Uuid::uuid4()->toString() . '-' .  basename($file["name"]);
        move_uploaded_file($file["tmp_name"], $target_file);
        $rel_path = str_replace('/var/www', '', $target_file);
        return $rel_path;
    }
    return null;
}
