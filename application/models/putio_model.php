<?php

class Putio_model extends CI_Model
{
   private $location = null;
   private $process = null;

   function __construct()
   {
      parent::__construct();
      $this->load->library('putio');

      $this->location = $this->config->item('putio_location');
      if (empty($this->location))
         $this->location = FCPATH . 'downloads/';

      $this->process_tv = $this->config->item('putio_process_tv');
      $this->process_movies = $this->config->item('putio_process_movies');

      $this->blackhole_movies = $this->config->item('blackhole_movies');
      $this->blackhole_tv = $this->config->item('blackhole_tv');

      $this->tv_path = $this->config->item('tv_path');
      $this->movie_path = $this->config->item('movie_path');

      $this->tv_folder_id = $this->config->item('tv_folder_id');
      $this->movie_folder_id = $this->config->item('movie_folder_id');



   }

   private function _get_files($type = 0)
   {
      if ($type == 0)
          $parent = $this->movie_folder_id;
      else
          $parent = $this->tv_folder_id;

      $objects = $this->putio->list_files($parent);
      echo "Retrieved file list fragment\n";

      $files = array();
      foreach ($objects as $object)
      {
         if ($object['content_type'] == 'application/x-directory')
            $files = array_merge($files, $this->_get_files($object['id']));
         else
            $files[] = $object;
      }

      return $files;
   }

   function upload_torrents($type = 0)
   {

      if ($type == 0) {
          echo "Uploading Movie Torrents\n";
          $location = $this->blackhole_movies;
          $parent = $this->movie_folder_id;
      }
      else {
          echo "Uploading TV Torrents\n";
          $location = $this->blackhole_tv;
          $parent = $this->tv_folder_id;
      }

      $this->load->helper(array('directory', 'file'));
      $files = directory_map($location, 0, true);
      foreach ($files as $file)
      {
         if ($file != "." && $file != "./") {
              echo "Uplading $file to $parent\n";
              echo $this->putio->add_torrent_file($location . $file, $parent)."\n";
              unlink($location . $file);
         }
      }
   }

   function get_files($type = 0)
   {
      return $this->_get_files($type);
   }

   function download_file($file, $type)
   {
      if (empty($file) || !isset($file['id']) || !isset($file['name']))
         return false;

         if ($type == 0)
          $location = $this->movie_path;
         else
          $location = $this->tv_path;

      //if (!file_exists($this->location . 'complete/'))
     //    mkdir($this->location . 'complete/');

      if (!file_exists($this->location . 'incomplete/'))
         mkdir($this->location . 'incomplete/');

      if ($this->putio->download_file($file['id'], $this->location . 'incomplete/' . $file['name'])) {
         mkdir($location . basename($file['name']));

         rename(
            $this->location . 'incomplete/' . $file['name'],
            $location . basename($file['name']) . '/' . $file['name']
         );
      } else
         return false;

      return true;
   }

   function file_exists($file)
   {
      if (empty($file) || !isset($file['name']))
         return false;

      if (
         file_exists($this->location . 'complete/' . $file['name'])
         || file_exists($this->location . 'incomplete/' . $file['name'])
         || file_exists($this->tv_path . $file['name'])
         || file_exists($this->movie_path . $file['name'])
      )
         return true;

      return false;
   }

   function delete_file($file)
   {
      if (empty($file) || !isset($file['id']))
         return false;

      return $this->putio->delete_file($file['id']);
   }

   function process_file($file)
   {
      if (empty($file) || !isset($file['name']))
         return false;

      $dirpath = $this->location . 'complete/' . basename($file['name']) . '/';
      $filepath = $dirpath . $file['name'];

      //if (filesize($filepath) < 3221225472)
      //   exec($this->process_tv . ' ' . $dirpath);
      //else
      //   exec($this->process_movies . ' ' . $dirpath);

      return true;
   }

}
