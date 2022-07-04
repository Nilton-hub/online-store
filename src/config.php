<?php

// DATABASE
const DATA_LAYER_CONFIG = [
    "driver" => "mysql",
    "host" => "localhost",
    "port" => "3306",
    "dbname" => "onlinestore",
    "username" => "root",
    "passwd" => "",
    "options" => [
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
        PDO::ATTR_CASE => PDO::CASE_NATURAL
    ]
];

// APP
const URL_BASE = 'http://localhost/projects/online-store';
// const URL_BASE = 'lace-up-carburetors.000webhostapp.com';

// DATE
const DATE_BR = 'd/m/Y H:i';
const DATE_APP = 'Y-m-d H:i:s';

// VIEWS
const VIEWS_PATH = __DIR__ . '/views';
const ASSETS_PATH =  URL_BASE . '/src/views/assets';

// PASSWORD
const PASSWORD_MIN_LEN = 8;
const PASSWORD_MAX_LEN = 40;

// UPLOADS
const UPLOAD_DIR = 'storage/uploads';
const UPLOAD_IMAGE_DIR = 'images';
const UPLOAD_FILE_DIR = 'files';
const UPLOAD_MEDIA_DIR = 'medias';
const UPLOAD_SEND_DIR = 'medias';

// IMAGE
const IMAGE_CACHE = UPLOAD_DIR . '/' . UPLOAD_IMAGE_DIR . '/cache';
const IMAGE_SIZE = 550;
const IMAGE_QUALITY = ['jpg' => 76, 'png' => 5];

// MAIL
const MAIL = [
    "host" => "smtp.sendgrid.net",
    "port" => "587",
    "user" => "apikey",
    "password" => "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",
    "from_name" => "Nilton Duarte",
    "from_email" => "tvirapegubeco@hotmail.com"
];
const API_SENDGRID_NAME = 'niltonduarteemails';
