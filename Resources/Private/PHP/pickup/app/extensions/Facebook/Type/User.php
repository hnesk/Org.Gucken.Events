<?php

namespace Facebook\Type;

use \Type\Number;
use \Type\String;
use \Type\Date;
use \Type\Url;

/**
 * An Class representing a Facebook User
 *
 * @author jk
 */
class User extends \Type\Base {

    /**
     *
     * @var \Facebook\Api
     */
    protected $api;

    /**
     *
     * @var Number
     */
    protected $id;

    /**
     * @var String
     */
    protected $name;

    /**
     * @var String
     */
    protected $firstName;

    /**
     * @var String
     */
    protected $lastName;

    /**
     * @var Url
     */
    protected $url;

    /**
     * @var String
     */
    protected $gender;

    /**
     * @var String
     */
    protected $timezone;

    /**
     * @var String
     */
    protected $locale;

    /**
     * @var String
     */
    protected $verified;

    /**
     * @var Date
     */
    protected $updated;


    public function __construct($id = 'me', $api = null) {
        $this->id = new String($id);

        $requestUri = new \Type\Url(\App::config('base_url'), 'play.php');
        $_SERVER['REQUEST_URI'] = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $requestUri->asString();
        $_SERVER['HTTP_HOST'] = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : $requestUri->getHost();

        $this->api = $api ? : \Facebook\Injector::injectFacebookApi();
    }


    /**
     *
     * @return User
     */
    protected function fetchProfile() {
        if (!$this->updated) {
            if ($this->id) {
                try {
                    $profile = $this->api->api('/' . $this->id);
                    $this->id = new String($profile['id']);
                    $this->name = new String($profile['name']);
                    $this->firstName = new String($profile['first_name']);
                    $this->lastName = new String($profile['last_name']);
                    $this->url = new Url($profile['link']);
                    $this->gender = new String($profile['gender']);
                    $this->timezone = new String(isset($profile['timezone']) ? $profile['timezone'] : '');
                    $this->locale = new String(isset($profile['locale']) ? $profile['locale'] : '');
                    $this->verified = new String(isset($profile['verified']) ? $profile['verified'] : '');
                    $this->updated = new Date($profile['updated_time']);
                } catch (FacebookApiException $e) {
                    var_dump($e);
                    $this->id = null;
                }
            }
        }
        return $this;
    }

    /**
     * @param string $since
     * @param string $until
     * @return Event\Collection
     */
    public function getEvents($since = null, $until = null) {
        $parameters = array(
            'fields'    =>  'id,name,description,start_time,end_time,updated_time,venue,privacy,location',
        );
        
        if ($since) {
            $parameters['since'] = $since;
        }
        if ($until) {
            $parameters['until'] = $until;
        }
        
        $events = $this->api->api('/' . $this->getId() . '/events','GET',$parameters);
        return Event\Collection\Factory::fromArray($events['data']);
    }

    
    public function getLoginUrl($permissions = array('user_events', 'offline_access')) {
        return $this->api->getLoginUrl(array(
            'redirect_uri' => (string) new \Type\Url(App::config('base_url'), 'play.php'),
            'scope' => \implode(',', $permissions)
        ));
    }

    public function getLogoutUrl() {
        return $this->api->getLogoutUrl(array(
            'next' => (string) new \Type\Url(\App::config('base_url'), 'play.php')
        ));
    }

    /* boring getters */

    /**
     *
     * @return stdClass
     */
    public function getNativeValue() {
        $this->fetchProfile();
        return (object) array(
                'id' => (int)(string)$this->id,
                'name' => (string)$this->name,
                'firstName' => (string)$this->firstName,
                'lastName' => (string)$this->lastName,
                'url' =>  (string)$this->url,
                'gender' =>  (string)$this->gender,
                'timezone' =>  (string)$this->timezone,
                'locale' =>  (string)$this->locale,
                'verified' =>  (string)$this->verified,
                'updated' =>  (string)$this->updated,
        );
    }

    /**
     *
     * @return Number
     */
    public function getId() {
        return $this->id;
    }

    /**
     *
     * @return String
     */
    public function getName() {
        $this->fetchProfile()->name;
    }

    /**
     *
     * @return String
     */
    public function getFirstName() {
        $this->fetchProfile()->firstName;
    }

    /**
     *
     * @return String
     */
    public function getLastName() {
        $this->fetchProfile()->lastName;
    }

    /**
     *
     * @return Url
     */
    public function getUrl() {
        $this->fetchProfile()->url;
    }


    /**
     *
     * @return String
     */
    public function getGender() {
        $this->fetchProfile()->gender;
    }

    /**
     *
     * @return String
     */
    public function getLocale() {
        $this->fetchProfile()->locale;
    }

    /**
     *
     * @return String
     */
    public function getVerified() {
        $this->fetchProfile()->verified;
    }


    /**
     *
     * @return Date
     */
    public function getUpdated() {
        $this->fetchProfile()->updated;
    }


}

?>