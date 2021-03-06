<?php

class User
{
    private $_db;
    private $_config;
    private $_data;
    private $_sessionName;
    private $_isLoggedIn = false;

    public function __construct($user = null)
    {
        $this->_config = Config::get('config/session');
        $this->_sessionName = $this->_config['sessions']['session_name'];
        $this->_db = DB::getInstance();

        if(!$user) {
            if(Session::exists($this->_sessionName)) {
                $user = Session::get($this->_sessionName);

                if($this->find($user)) {
                    $this->_isLoggedIn = true;
                } else {
                    //logout
                }
            }
        } else {
            $this->find($user);
        }
    }

    public function create($fields = array())
    {
        if(!$this->_db->insert('users', $fields)) {
            throw new Exception ('There was a problem creating an account.');
        }
    }

    public function find($user = null)
    {
        if($user) {
            $field = (is_numeric($user)) ? 'id' : 'username';
            $data = $this->_db->get('*', 'users', array($field, '=', $user));

            if($data->count()) {
                $this->_data = $data->first();
                return true;
            }
        }
        return false;
    }

    public function login($username = null, $password = null)
    {
		if($username){
			$user = $this->find($username);
			if($user) {
				$passwordDB = $this->data()->password;
				$salt = $this->data()->salt;
				$givenPassword = Hash::make($password, $salt);
				if($passwordDB === $givenPassword) {
					Session::put($this->_sessionName, $this->data()->id);
					return true;
				}
			}
		}
        return false;
    }

    public function logout()
    {
        Session::delete($this->_sessionName);
    }

    public function data()
    {
        return $this->_data;
    }

    public function check()
    {
        return $this->_isLoggedIn;
    }
}
