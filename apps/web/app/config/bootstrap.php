<?php

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         0.10.8
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

/*
 * Configure paths required to find CakePHP + general filepath constants
 */
require __DIR__ . '/paths.php';

define('UPLOAD_BASE_URL', 'upload');
define('UPLOAD_DIR', WWW_ROOT . UPLOAD_BASE_URL . DS);
define('USER_PAGES', 'sitepages');
define('USER_PAGES_DIR', WWW_ROOT . USER_PAGES . DS);
define('USER_JSON_URL', 'jsdata'); // USER_PAGES_DIR . [username] . DS . USER_JSON_URL
define('HOME_DATA_NAME', '');
define('SITE_DATA_NAME', 'sitehome');

define('SITE_PAGES', 'sitepages');
define('SITE_PAGES_DIR', WWW_ROOT . SITE_PAGES . DS);

// 記事のカテゴリ機能を使う
define('CATEGORY_FUNCTION_ENABLED', true);
define('CATEGORY_SORT', true); // 稼働後に変更しちゃだめ

// define('DATE_ZERO', '1900-01-01');
define('DATE_ZERO', '0000-00-00');
define('DATE_FORMAT', 'Y-m-d');
define('END_DATE', '2999-12-31');
define('END_TIME', '23:59');
define('OPEN_TIME', '00:00');
/*
 * Bootstrap CakePHP.
 *
 * Does the various bits of setup that CakePHP needs to do.
 * This includes:
 *
 * - Registering the CakePHP autoloader.
 * - Setting the default application paths.
 */
require CORE_PATH . 'config' . DS . 'bootstrap.php';

use Cake\Cache\Cache;
use Cake\Console\ConsoleErrorHandler;
use Cake\Core\App;
use Cake\Core\Configure;
use Cake\Core\Configure\Engine\PhpConfig;
use Cake\Core\Plugin;
use Cake\Database\Type;
use Cake\Datasource\ConnectionManager;
use Cake\Error\ErrorHandler;
use Cake\Http\ServerRequest;
use Cake\Log\Log;
use Cake\Mailer\Email;
use Cake\Mailer\TransportFactory;
use Cake\Utility\Inflector;
use Cake\Utility\Security;

/**
 * Uncomment block of code below if you want to `.env` file during development.
 * You should copy `config/.env.default to `config/.env` and set/modify the
 * variables as required.
 */
// if (!env('APP_NAME') && file_exists(CONFIG . '.env')) {
//     $dotenv = new \josegonzalez\Dotenv\Loader([CONFIG . '.env']);
//     $dotenv->parse()
//         ->putenv()
//         ->toEnv()
//         ->toServer();
// }

/*
 * Read configuration file and inject configuration into various
 * CakePHP classes.
 *
 * By default there is only one configuration file. It is often a good
 * idea to create multiple configuration files, and separate the configuration
 * that changes from configuration that does not. This makes deployment simpler.
 */

//DBやdebugの切替
function get_config_name()
{
    //ドメインでConfigを切り替える。
    if (is_included_host(['demo-v5m', 'dev', 'localhost', 'caters', 'test', 'local'])) {
        return 'app_develop';
    }
    //ドメインがない(コンソール処理)場合はドキュメントルートのパスで切り替える。
    if (!env('HTTP_HOST') && is_included_docRoot(['_test'])) {
        return 'app_develop';
    }
    return 'app_honban';
}

try {
    Configure::config('default', new PhpConfig());
    Configure::load('app', 'default', false);

    //環境ごとにDBやdebugを切り替える。
    Configure::load(get_config_name(), 'default');
} catch (\Exception $e) {
    exit($e->getMessage() . "\n");
}

// 公開側の出力をJsonにする
Configure::write('Contents.enabledJson', true);

/*
 * Load an environment local configuration file.
 * You can use a file like app_local.php to provide local overrides to your
 * shared configuration.
 */
//Configure::load('app_local', 'default');

/*
 * When debug = true the metadata cache should only last
 * for a short time.
 */
if (Configure::read('debug')) {
    Configure::write('Cache._cake_model_.duration', '+2 minutes');
    Configure::write('Cache._cake_core_.duration', '+2 minutes');
    // disable router cache during development
    Configure::write('Cache._cake_routes_.duration', '+2 seconds');
}

/*
 * Set server timezone to UTC. You can change it to another timezone of your
 * choice but using UTC makes time calculations / conversions easier.
 * Check http://php.net/manual/en/timezones.php for list of valid timezone strings.
 */
date_default_timezone_set(Configure::read('App.defaultTimezone'));

/*
 * Configure the mbstring extension to use the correct encoding.
 */
mb_internal_encoding(Configure::read('App.encoding'));

/*
 * Set the default locale. This controls how dates, number and currency is
 * formatted and sets the default language to use for translations.
 */
ini_set('intl.default_locale', Configure::read('App.defaultLocale'));

/*
 * Register application error and exception handlers.
 */
$isCli = PHP_SAPI === 'cli';
if ($isCli) {
    (new ConsoleErrorHandler(Configure::read('Error')))->register();
} else {
    (new ErrorHandler(Configure::read('Error')))->register();
}

/*
 * Include the CLI bootstrap overrides.
 */
if ($isCli) {
    require __DIR__ . '/bootstrap_cli.php';
}

/*
 * Set the full base URL.
 * This URL is used as the base of all absolute links.
 *
 * If you define fullBaseUrl in your config file you can remove this.
 */
if (!Configure::read('App.fullBaseUrl')) {
    $s = null;
    if (env('HTTPS')) {
        $s = 's';
    }

    $httpHost = env('HTTP_HOST');
    if (isset($httpHost)) {
        Configure::write('App.fullBaseUrl', 'http' . $s . '://' . $httpHost);
    }
    unset($httpHost, $s);
}

Cache::setConfig(Configure::consume('Cache'));
ConnectionManager::setConfig(Configure::consume('Datasources'));
TransportFactory::setConfig(Configure::consume('EmailTransport'));
Email::setConfig(Configure::consume('Email'));
Log::setConfig(Configure::consume('Log'));
Security::setSalt(Configure::consume('Security.salt'));

/*
 * The default crypto extension in 3.0 is OpenSSL.
 * If you are migrating from 2.x uncomment this code to
 * use a more compatible Mcrypt based implementation
 */
//Security::engine(new \Cake\Utility\Crypto\Mcrypt());

/*
 * Setup detectors for mobile and tablet.
 */
ServerRequest::addDetector('mobile', function ($request) {
    $detector = new \Detection\MobileDetect();

    return $detector->isMobile();
});
ServerRequest::addDetector('tablet', function ($request) {
    $detector = new \Detection\MobileDetect();

    return $detector->isTablet();
});

/*
 * Enable immutable time objects in the ORM.
 *
 * You can enable default locale format parsing by adding calls
 * to `useLocaleParser()`. This enables the automatic conversion of
 * locale specific date formats. For details see
 * @link https://book.cakephp.org/3.0/en/core-libraries/internationalization-and-localization.html#parsing-localized-datetime-data
 */
/** @var \Cake\Database\Type\DateTimeType $time */
$time = Type::build('time');
$time->useImmutable();

/** @var \Cake\Database\Type\DateTimeType $date */
$date = Type::build('date');
$date->useImmutable();

/** @var \Cake\Database\Type\DateTimeType $datetime */
$datetime = Type::build('datetime');
$datetime->useImmutable();

/** @var \Cake\Database\Type\DateTimeType $timestamp */
$timestamp = Type::build('timestamp');
$timestamp->useImmutable();

/*
 * Custom Inflector rules, can be set to correctly pluralize or singularize
 * table, model, controller names or whatever other string is passed to the
 * inflection functions.
 */
//Inflector::rules('plural', ['/^(inflect)or$/i' => '\1ables']);
//Inflector::rules('irregular', ['red' => 'redlings']);
//Inflector::rules('uninflected', ['dontinflectme']);
//Inflector::rules('transliteration', ['/å/' => 'aa']);

/*
 * Plugins need to be loaded manually, you can either load them one by one or all of them in a single call
 * Uncomment one of the lines below, as you need. make sure you read the documentation on Plugin to use more
 * advanced ways of loading plugins
 *
 * Plugin::loadAll(); // Loads all plugins at once
 * Plugin::load('Migrations'); //Loads a single plugin named Migrations
 *
 */

/*
 * Only try to load DebugKit in development mode
 * Debug Kit should not be installed on a production system
 */
if (Configure::read('debug')) {
    Configure::write('DebugKit.forceEnable', true);
    \App\Application::addPlugin('DebugKit', ['bootstrap' => true, 'routes' => true]);
}

// Plugin::load('Admin');
\App\Application::addPlugin('DebugKit');

function is_included_host($targets = array())
{
    foreach ($targets as $target) {
        if (strpos(env('HTTP_HOST'), $target) !== false) {
            return true;
        }
    }
    return false;
}

function is_included_docRoot($targets = array())
{
    foreach ($targets as $target) {
        if (strpos(env('SCRIPT_FILENAME'), $target) !== false) {
            return true;
        }
    }
    return false;
}

class Image
{
    /**
     * 画像（バイナリ）のEXif情報を元に回転する
     */
    public function rotateFromBinary($binary)
    {
        $exif_data = $this->getExifFromBinary($binary);
        if (empty($exif_data['Orientation']) || in_array($exif_data['Orientation'], [1, 2])) {
            return $binary;
        }
        return $this->rotate($binary, $exif_data);
    }

    /**
     * バイナリデータからexif情報を取得
     */
    private function getExifFromBinary($binary)
    {
        $temp = tmpfile();
        fwrite($temp, $binary);
        fseek($temp, 0);

        $meta_data = stream_get_meta_data($temp);
        $exif_data = @exif_read_data($meta_data['uri']);

        fclose($temp);
        return $exif_data;
    }

    /**
     * 画像を回転させる
     */
    private function rotate($binary, $exif_data)
    {
        ini_set('memory_limit', '256M');

        $src_image = imagecreatefromstring($binary);

        $degrees = 0;
        $mode = '';
        switch ($exif_data['Orientation']) {
            case 2: // 水平反転
                $mode = IMG_FLIP_VERTICAL;
                break;
            case 3: // 180度回転
                $degrees = 180;
                break;
            case 4: // 垂直反転
                $mode = IMG_FLIP_HORIZONTAL;
                break;
            case 5: // 水平反転、 反時計回りに270回転
                $degrees = 270;
                $mode = IMG_FLIP_VERTICAL;
                break;
            case 6: // 反時計回りに270回転
                $degrees = 270;
                break;
            case 7: // 反時計回りに90度回転（反時計回りに90度回転） 水平反転
                $degrees = 90;
                $mode = IMG_FLIP_VERTICAL;
                break;
            case 8: // 反時計回りに90度回転（反時計回りに90度回転）
                $degrees = 90;
                break;
        }

        if (!empty($mode)) {
            imageflip($src_image, $mode);
        }

        if ($degrees > 0) {
            $src_image = imagerotate($src_image, $degrees, 0);
        }

        ob_start();
        if (empty($exif_data['MimeType']) || $exif_data['MimeType'] == 'image/jpeg') {
            imagejpeg($src_image);
        } elseif ($exif_data['MimeType'] == 'image/png') {
            imagepng($src_image);
        } elseif ($exif_data['MimeType'] == 'image/gif') {
            imagegif($src_image);
        }
        imagedestroy($src_image);
        return ob_get_clean();
    }
}
