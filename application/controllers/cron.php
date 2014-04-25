<?php

class Cron extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model(array('putio_model'));
		$this->lockfile = $this->config->item('lock_file');
	}

	private function _is_locked()
	{
		if (file_exists($this->lockfile))
		{
	        // check if it's stale
	        $lockingPID = trim(file_get_contents($this->lockfile));

			// Get all active PIDs.
	        $pids = explode("\n", trim(`ps -e | awk '{print $1}'`));

	        // If PID is still active, return true
	        if(in_array($lockingPID, $pids)) return true;

	        // Lock-file is stale, so kill it.
	        // Then move on to re-creating it.
	        unlink($this->lockfile);
	    }

	    file_put_contents($this->lockfile, getmypid() . "\n");
	    return false;
	}

	function sync()
	{
		if ($this->_is_locked()) {
			return;
		}

		// upload the files
		$this->putio_model->upload_torrents(0);
		$this->putio_model->upload_torrents(1);

          echo "Downloading files\n";
          for ($i=0; $i<2; $i++) {
               echo "Getting files of type $i\n";
     		// get the file list
     		$files = $this->putio_model->get_files($i);

     		// download each file
     		foreach ($files as &$file)
     		{
     			if ($this->putio_model->file_exists($file))
     				continue;

                    echo "Downloading $file\n";
     			if ($this->putio_model->download_file($file))
     				$this->putio_model->delete_file($file);

     		}
		}

	}
}