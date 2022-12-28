<?php


if (isset($_POST["app-name"]))
{

	$src          = __DIR__ . '/build';
	$dest         = __DIR__ . '/dist';
	$exclude      = ['.', '..'];
	$new_app_name = '';

	function copyDir($src, $dest, $exclude, &$new_app_name)
	{

		!is_dir($dest) ? mkdir($dest) : '';

		foreach (scandir($src) as $item)
		{

			$srcPath  = $src . '/' . $item;
			$destPath = $dest . '/' . $item;

			// Name App Directory
			if (is_dir($srcPath) && $item =='customapp')
			{
				$new_app_name = str_replace(' ', '-', strtolower($_POST["app-name"]));
				$destPath     = $dest . '/' . $new_app_name;
			}

			if (!in_array($item, $exclude))
			{

				if (is_dir($srcPath))
				{
					copyDir($srcPath, $destPath, $exclude, $new_app_name);
				}
				else
				{

					copy($srcPath, $destPath);

					if (checksum($srcPath, $destPath))
					{
						switch (basename($destPath)) {
							case 'application.php':
								fileReplaceContent(
									$destPath,
									'CustomAppApplication', 
									str_replace(' ', '', ucwords(strtolower($_POST["app-name"]))) . 'Application');
								break;

							case 'application.xml':
								fileReplaceContent(
									$destPath,
									'<name>Custom App</name>', 
									'<name>' . $_POST['app-name'] . '</name>');
								fileReplaceContent(
									$destPath,
									'<group>custom-app</group>', 
									'<group>' . str_replace(' ', '-', strtolower($_POST["app-name"])) . '</group>');
								break;

							case 'positions.config':
								fileReplaceContent(
									$destPath,
									'custom-app.sample-item-typ', 
									str_replace(' ', '-', strtolower($_POST['app-name'])) . '.sample-item-typ');
								break;
						}
						// echo 'Success transfer for:' . $srcPath . '<br>';
					}
					else
					{
						// echo 'Failed transfer for:' . $srcPath . '<br>';
					}
				}
			}
		}

		// echo "$dest/$new_app_name<br />";

	}

	function fileReplaceContent($path, $oldContent, $newContent)
	{
	    $str = file_get_contents($path);
	    $str = str_replace($oldContent, $newContent, $str);
	    file_put_contents($path, $str);
	}

	function checksum($src,$dest)
	{
		if (file_exists($src) and file_exists($dest))
		{
			return md5_file($src) == md5_file($dest) ? true : false;
		}
		else
		{
			return false;
		}
	}

	function recurseRmdir($dir)
	{
		$files = array_diff(scandir($dir), array('.','..'));
		foreach ($files as $file)
		{
			(is_dir("$dir/$file") && !is_link("$dir/$file")) ? recurseRmdir("$dir/$file") : unlink("$dir/$file");
		}
		return rmdir($dir);
	}

	Class ZipArchiver
	{
	    
	    /**
	     * Zip a folder (including itself).
	     * 
	     * Usage:
	     * Folder path that should be zipped.
	     * 
	     * @param $sourcePath string 
	     * Relative path of directory to be zipped.
	     * 
	     * @param $outZipPath string 
	     * Path of output zip file. 
	     *
	     */

	    public static function zipDir($sourcePath, $outZipPath)
	    {

	        $z = new ZipArchive();
	        $z->open($outZipPath, ZipArchive::CREATE);

	        self::dirToZip($sourcePath, $z, strlen("$sourcePath/"));
	        
	        $z->close();
	        
	        return true;
	    }
	    
	    /**
	     * Add files and sub-directories in a folder to zip file.
	     * 
	     * @param $folder string
	     * Folder path that should be zipped.
	     * 
	     * @param $zipFile ZipArchive
	     * Zip file where files end up.
	     * 
	     * @param $exclusiveLength int 
	     * Number of text to be excluded from the file path. 
	     *
	     */

	    private static function dirToZip($folder, &$zipFile, $exclusiveLength)
	    {

	        $handle = opendir($folder);


	        while (FALSE !== $f = readdir($handle))
	        {

	        	// echo print_r($f);
	            // Check for local/parent path or zipping file itself and skip

	            if ($f != '.' && $f != '..' && $f != basename(__FILE__))
	            {
	                $filePath = "$folder/$f";

	                // Remove prefix from file path before add to zip
	                $localPath = substr($filePath, $exclusiveLength);
	                if (is_file($filePath))
	                {
	                    $zipFile->addFile($filePath, $localPath);
	                }
	                elseif (is_dir($filePath))
	                {
	                    // Add sub-directory
	                    $zipFile->addEmptyDir($localPath);
	                    self::dirToZip($filePath, $zipFile, $exclusiveLength);
	                }
	            }
	        }
	        closedir($handle);
	    }
	    
	}

	// Delete Old Folder
	recurseRmdir($dest);

	// Create New Copy
	copyDir($src, $dest, $exclude, $new_app_name);


	$zipper = new ZipArchiver;

	// Path of the directory to be zipped
	$dirPath = "$dest/$new_app_name";

	// Path of output zip file
	$zipPath = "$dest/$new_app_name.zip";

	// Create zip archive
	$zip = $zipper->zipDir($dirPath, $zipPath);

	if ($zip)
	{
	    recurseRmdir($dest . '/' . $new_app_name);

		// $archive_file_name = $dest . '/' . $new_app_name . '.zip';

		echo $new_app_name . '.zip';

		// header("Location: $archive_file_name");

	}
	else
	{
	    echo 'Failed to create ZIP.';
	}



    exit;

	// echo $_POST["app-name"];
}

?>