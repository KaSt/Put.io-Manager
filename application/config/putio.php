<?php
/**
 * Put your Put.IO OAuth Token here.
 * It requires that you register an API
 * application through Put.io
 * You can find it at:
 * https://put.io/v2/oauth2/register
 *
 * EX. 'PAYURGLP'
 */
$config['putio_key'] = 'W3VIBTRZ';

/**
 * This is where we will put your
 * downloads, it will default to
 * the root directory of the project.
 *
 * EX. /var/www/putio/downloads
 */
$config['putio_location'] =  FCPATH . 'downloads/';

/**
 * This is where we look for torrent
 * files to upload to your putio
 * account.
 */
$config['blackhole_movies'] = '/Various/Complete/Movies/Torrents/';
$config['blackhole_tv'] = '/Various/Complete/TV/torrents/';


/**
 * What do we run after when we
 * got your file?
 *
 * EX. 'python /usr/share/sickbeard/autoProcessTV/sabToSickBeard.py'
 */
//$config['putio_process_tv'] = 'python /usr/share/sickbeard/autoProcessTV/sabToSickBeard.py';
//$config['putio_process_movies'] = 'python /home/sjlu/sabToCouchPotato.py -d';

/**
 * Where should we place movies?
 */
$config['movie_path'] = '/Various/Complete/Movies/';
$config['tv_path'] = '/Various/Complete/TV/';


/**
 * PUT.IO Folder Ids
 */
$config['movie_folder_id'] = 178712665;
$config['tv_folder_id'] = 178712667;



/**
 * Where should the lock file go?
 */
$config['lock_file'] =  FCPATH  . 'putio.lock';

?>
