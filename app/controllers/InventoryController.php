<?php

class InventoryController extends \Phalcon\Mvc\Controller
{

    public function detailAction($removal_id)
    {
        $categories = RemovalInventory::findByRemovalId($removal_id);
        $data = array();
        foreach($categories as $category) {
            $c = $category->toArray();
            $c['items'] = json_decode($c['items']);
            $data[] = $c;
        }
        $this->view->categories = $data;

    }

    public function mapAction() {
        $from_lat = -38.665564;
        $from_lon = 146.436128;
        $to_lat = -27.17079;
        $to_lon = 152.954767;
        $width = 650;
        $height = 500;
        $cen_lat = ($from_lat + $to_lat)/2;
        $cen_lon = ($from_lon + $to_lon)/2;
        $zoom = $this->getBoundsZoomLevel($from_lat, $from_lon, $to_lat, $to_lon, $width, $height);


        $url = "https://maps.googleapis.com/maps/api/staticmap?center=$cen_lat,$cen_lon&zoom=$zoom&size=" . $width . "x" . $height . "&markers=$from_lat,$from_lon&markers=$to_lat,$to_lon&path=color:0x0000ff|weight:5|$from_lat,$from_lon|$to_lat,$to_lon&key=AIzaSyDX3uDXdUb5i86vMGTW8hZPH01Zb0E86WI";
        echo $url;
        die();

    }

    

    function getBoundsZoomLevel($from_lat, $from_lon, $to_lat, $to_lon, $width, $height) {
        $global_width = 256;
        $zoom_max = 21;
        $latFraction = ($this->latRad($to_lat) - $this->latRad($from_lat)) / M_PI;
        $lonDiff = $to_lon - $from_lon;
        $lonFraction = (($lonDiff < 0) ? ($lonDiff + 360) : $lonDiff) / 360;
        $latZoom = $this->zoom($height, $global_width, $latFraction);
        $lonZoom = $this->zoom($width, $global_width, $lonFraction);
        $zoom = min(min($latZoom, $lonZoom), $zoom_max);
        return (int) $zoom;
    }

    function latRad($lat) {
        $sin = sin($lat * M_PI / 180);
        $radX2 = log((1 + $sin) / (1 - $sin)) / 2;
        return max(min($radX2, M_PI), -M_PI) / 2;
    }

    function zoom($maxPx, $worldPx, $fraction) {
        $ln2 = 0.693147180559945309417;
        return floor(log($maxPx / $worldPx / $fraction) / $ln2);
    }

}

