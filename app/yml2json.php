#!/usr/bin/env php
<?php

echo json_encode(
    yaml_parse(file_get_contents('php://stdin')),
    JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
);
