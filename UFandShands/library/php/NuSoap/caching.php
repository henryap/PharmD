<?php
	/*
	 * Caching	A small PHP class to get data and cache it
	 * Author:	Gaya Kessler
	 * URL:		http://www.gayadesign.com/
	 */

	class Caching {

		var $filePath = "";
		var $apiURI = "";

		function __construct($filePath, $apiURI) {
			//check if the file path and api URI are specified, if not: break out of construct.
			if (strlen($filePath) > 0 && strlen($apiURI) > 0) {
				//set the local file path and api path
				$this->filePath = $filePath;
				$this->apiURI = $apiURI;

				//does the file need to be updated?
				if ($this->checkForRenewal()) {

					//get the data you need
					$xml = $this->getExternalInfo();

					//save the data to your file
					$this->stripAndSaveFile($xml);

					return true;
				} else {
					//no need to update the file
					return true;
				}

			} else {
				echo "No file path and / or api URI specified.";
				return false;
			}
		}

		function checkForRenewal() {
			//set the caching time (in seconds)
			$cachetime = (60 * 60 * 24 * 7); //one week

			//get the file time
			$filetimemod = filemtime($this->filePath) + $cachetime;

			//if the renewal date is smaller than now, return true; else false (no need for update)
			if ($filetimemod < time()) {
				return true;
			} else {
				return false;
			}
		}

		function getExternalInfo() {
			if ($xml = @simplexml_load_file($this->apiURI)) {
				return $xml;
			} else {
				return false;
			}
		}

		function stripAndSaveFile($xml) {
			//put the artists in an array
			$artists = $xml->weeklyartistchart->artist;

			//building the xml object for SimpleXML
			$output = new SimpleXMLElement("<artists></artists>");

			//get only the top 10
			for ($i = 0; $i < 10; $i++) {

				//create a new artist
				$insert = $output->addChild("artist");

				//insert name and playcount childs to the artist
				$insert->addChild("name", $artists[$i]->name);
				$insert->addChild("playcount", $artists[$i]->playcount);

			}

			//save the xml in the cache
			file_put_contents($this->filePath, $output->asXML());
		}

	}

?>
