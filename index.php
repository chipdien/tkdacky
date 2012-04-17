<?php
define('IN_MEDIA',true);
require __DIR__.'/setting.php';

// Init idiorm for fswd
require_once __DIR__.'/lib/idiorm.php';
ORM::configure('mysql:host='.DB_HOST.';port='.DB_PORT.';dbname='.DB_NAME.'');
ORM::configure('username', DB_USER);
ORM::configure('password', DB_PASS);
ORM::configure('driver_options', array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
ORM::configure('id_column_overrides', array(
    'alluser' => 'UserID',
    'alluser_copy' => 'UserID',
    'citygeneralinfo' => 'Name',
    'emperorinfo' => 'UserID',
    'goodsinfo' => 'GoodsID',
    'officialposinfo' => 'Grade',
    'onlineuser' => 'UserID',
));

// Use the Fat-Free Framework
$main=require_once __DIR__.'/lib/base.php';

$main->set('AUTOLOAD','app/');
$main->set('DEBUG',3);
$main->set('FONTS','fonts/');
$main->set('GUI','gui/');
$main->set('DB', new DB('mysql:host='.DB_HOST_4R.';port='.DB_PORT_4R.';dbname='.DB_NAME_4R.'', DB_USER_4R, DB_PASS_4R));

/*
 * SET GLOBAL VARS
 */
$main->set('menu',
    array_merge(
        array(
            'Tổng Quan'=>'/',
            'Nhận Thưởng'=>'/nhan-thuong',
            'Chợ'=>'/cho-troi',
            'Trang Cá Nhân'=>'/trang-ca-nhan'
        ),
            
        in_array($main->get('SESSION.id'), $ModCP) ?
        array(
            'Trang Điều Hành'=>'/trang-dieu-hanh'
        ):
        array(
            
        )
    )
);

/*
 * MAIN ROUTER
 */

$main->route('GET /', 'App->homepage');

$main->route('GET /dang-nhap', 'App->login');
$main->route('GET /dang-xuat', 'App->logout');
$main->route('POST /dang-nhap', 'App->auth');

$main->route('GET /trang-ca-nhan', 'App->profile');
$main->route('POST /trang-ca-nhan/doi-mat-khau', 'App->changePassword');

$main->route('GET /cho-troi', 'App->shop');
$main->route('POST /cho-troi/mua-vat-pham', 'App->buyItem');
$main->route('POST /cho-troi/ban-vat-pham', 'App->sellItem');

$main->route('GET /nhan-thuong', 'App->present');
$main->route('GET /nhan-thuong/qua-tang-quan-chuc', 'App->getOfficialPosPresent');
$main->route('GET /nhan-thuong/qua-tang-tan-thu', 'App->getNewbiePresent');

$main->run();

?>