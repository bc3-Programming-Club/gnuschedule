<?php

require 'model.php';

class User extends Model {
    public $email;
    public $password_hash;
    public $is_admin = FALSE;
}

class Reservation extends Model {
    public $staff_email;
    public $client_email;
    public $begins_at;
    public $ends_at;
    public $comment;
}

class Confirmation extends Model {
    public $resolved = FALSE;
    
    function renew($ttl=3600) {
        $this->resolved = FALSE;
        $this->createdAt = time();
        $this->ttl = $ttl; // TTL = Time-to-Live AKA when it should expire
        
        /* 
         * `uniqid()` creates a long string of random characters. The code should be unique,
         * no matter how many active confirmations exist. This could be substituted for a 
         * six-digit code as long as it gets added to a cumulative do-not-use list to avoid
         * repetition.
         */
        $this->code = uniqid();
        
        return $this->code;
    }
    
    public function age() {
        return time() - $this->createdAt;
    }
    
    public function expired() {
        return $this->age() > $this->ttl;
    }
    
    // static:
    
    static function of(string $target) {
        $found = self::findOne(['target' => $target]);

        if ($found !== NULL) {
            return $found->resolved;
        } else {
            return FALSE;
        }
        
    }
    
    static function resolve(string $code) {
        foreach (self::all() as &$object) {
            if ($object->code === $code && $object->expired() === FALSE) {
                $object->resolved = TRUE;
                return TRUE;
            }   
        }
        return FALSE;
    }
}
