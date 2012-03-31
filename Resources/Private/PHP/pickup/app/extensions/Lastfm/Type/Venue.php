<?php

namespace Lastfm\Type;

/**
 * An Class representing a last.fm Artist
 *
 * @author jk
 */
class Venue extends \Type\Base {

    protected $id;
    
    protected $name;
    
    protected $city;
    
    protected $country;
    
    protected $street;
    
    protected $postalcode;
    
    protected $latitude;
    
    protected $longitude;
    
    protected $lastFmUrl;
    
    protected $url;
    
    /**
     * 
     * @param string $id
     * @param string $name
     * @param string $city
     * @param string $country 
     * @param string $street
     * @param string $postalcode
     * @param float $latitude
     * @param float $longitude
     * @param \Type\Url $lastFmUrl
     * @param \Type\Url $url
     */
    public function __construct($id, $name, $city, $country, $street, $postalcode, $latitude, $longitude, $lastFmUrl, $url) {
        $this->id = $id;
        $this->name = $name;
        $this->city = $city;
        $this->country = $country;
        $this->street = $street;
        $this->postalcode = $postalcode;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->lastFmUrl = $lastFmUrl;
        $this->url = $url;        
    }

    public function __toString() {
        return (string)$this->name.', '.$this->city;
    }
    
    public function getName() {
        return $this->name;
    }
    
    public function getStreet() {
        return $this->street;
    }
    
    public function getCountry() {
        return $this->country;
    }
    
    public function getPostalCode() {
        return $this->postalcode;
    }
    
    public function getLatitude() {
        return $this->latitude;
    }

    public function getLongitude() {
        return $this->longitude;
    }
    
    /**
     *
     * @return Type\Url
     */
    public function getLastFmUrl() {
        return $this->lastFmUrl;
    }
    
    /**
     *
     * @return \Type\Url
     */
    public function getUrl() {
        return $this->url;
    }       
    
    public function getCity() {
        return $this->city;
    }
    
    public function getId() {
        return $this->id;
    }
    
    
    
    
    public function getNativeValue() {
        $values = array();
        foreach (get_object_vars($this) as $key => $value) {
            if (!is_scalar($value)) {
                if ($value instanceof Type\Base) {
                    $value = $value->getNativeValue();
                } else {
                    $value = (string)$value;
                }
            }
            
            $values[$key] = $value;
        }
        return $values;
    }
           

}
?>