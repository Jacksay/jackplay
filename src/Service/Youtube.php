<?php
/**
 * Created by PhpStorm.
 * User: jacksay
 * Date: 25/05/2018
 * Time: 16:24
 */

namespace App\Service;


use Doctrine\ORM\EntityManager;

class Youtube
{
    private $API_KEY;
    private $googleClient;
    private $googleServiceYoutube;
    private $entityManager;

    private function getEntityManager(){
        return $this->entityManager;
    }


    /**
     * @return \Google_Client
     */
    private function getGoogleClient(){
        if( !$this->googleClient ){
            $this->googleClient = new \Google_Client();
            $this->googleClient->setDeveloperKey($this->API_KEY);
        }
        return $this->googleClient;
    }

    /**
     * @return \Google_Service_YouTube
     */
    private function getGoogleServiceYoutube(){
        if( !$this->googleServiceYoutube ){
            $this->googleServiceYoutube = new \Google_Service_YouTube($this->getGoogleClient());
        }
        return $this->googleServiceYoutube;
    }

    /**
     * Youtube constructor.
     * @param $API_KEY
     */
    public function __construct($apikey, EntityManager $entityManager)
    {
        $this->API_KEY = $apikey;
        $this->entityManager = $entityManager;
    }


    public function updatePlayfile($playfileKey){
        $nextPageToken = '';
        echo "<pre>";
        do {
            $playlistItemsResponse = $this->getGoogleServiceYoutube()->playlistItems->listPlaylistItems('snippet', array(
                'playlistId' => $playfileKey,
                'maxResults' => 50,
                'pageToken' => $nextPageToken));

            foreach ($playlistItemsResponse['items'] as $playlistItem) {
                $description = $playlistItem['snippet']["description"];
                $title = $playlistItem['snippet']["title"];
                $images = $playlistItem['snippet']["thumbnails"]["medium"]->url;
                $publichedAt = $playlistItem['snippet']["publishedAt"];
                $videoId = $playlistItem['snippet']["resourceId"]->videoId;
                //publishedAt

                echo "<img src=\"$images\">";
            }

            $nextPageToken = $playlistItemsResponse['nextPageToken'];
        } while ($nextPageToken <> '');
        die($playfileKey);
    }

}