<?php
//update 'session.cookie_lifetime = 604800' (7 days) on php.ini
require 'BrowserDetection.php';
$browser = new Wolfcast\BrowserDetection();

class _account {

	private $id;
	private $username;
	private $authenticated;

	public function __construct(){
		$this->id = NULL;
		$this->username = NULL;
		$this->authenticated = FALSE;
		$this->updateTime = NULL;
	}
	
	public function __destruct(){
		
	}

	public function login(string $username, string $password): bool {
		global $conn;
		$conn->link = $conn->connect();

        if(!empty($username) && !empty($password)){
            if($stmt = $conn->link->prepare("SELECT id, password FROM users WHERE username = ?")){
                $stmt->bind_param('s', $username);
                try{
	                $stmt->execute();
	                $stmt->store_result();
				}
				catch(Exception $e){
					throw new Exception('Erro ao conectar com a base de dados: '. $e);
				}
                if($stmt->num_rows > 0){
                    $stmt->bind_result($db_account_id, $db_account_password);
                    $stmt->fetch();

                    if(password_verify($password, $db_account_password)){
                        session_regenerate_id();

                        $_SESSION['loggedin'] = TRUE;
                        $_SESSION['id'] = $db_account_id;
                        $_SESSION['username'] = $username;
                        $_SESSION['updateTime'] = strtotime('NOW');

                        $this->id = $db_account_id;
						$this->username = $username;
						$this->authenticated = TRUE;
						$this->updateTime = $_SESSION['updateTime'];

                        //Verify if the current password's hash needs to be updated to newest algorithm
                        if(password_needs_rehash($db_account_password, $password)){
                            if($stmt = $conn->link->prepare("UPDATE users SET password = ? WHERE id = ?")){
                                $stmt->bind_param('si', $new_hash, $refer_id);

                                $new_hash = password_hash($password, PASSWORD_DEFAULT);
                                $refer_id = $db_account_id;
                                $stmt->execute();
                                echo '<div class="box-msg sucess" style="top:100%;">Obaa! seu hash foi atualizado para a versão mais recente!</div>';
                            }
                        }

                        /* Register the current Sessions on the database */
						$this->registerLoginSession();

                        return TRUE;
                    } else{
                    	$stmt->close();
				        $conn->disconnect($conn->link);
                        throw new Exception(ERROR_LOGIN_PASSWORD);
                    }
                } else{
                	$stmt->close();
			        $conn->disconnect($conn->link);
                    throw new Exception(ERROR_LOGIN_USERNAME);
                }
            }
        } else {
	        $conn->disconnect($conn->link);
            throw new Exception(ERROR_LOGIN_BLANK);
        }
	}

	private function registerLoginSession(){
		global $conn;
		global $browser;
		$conn->link = $conn->connect();

		if(session_status() == PHP_SESSION_ACTIVE){
			if($stmt = $conn->link->prepare("REPLACE INTO users_sessions (session_id, user_id, login_time, user_agent, user_OS) VALUES (?, ?, NOW(), ?, ?)")){
				$stmt->bind_param('siss', $session, $userid, $user_agent, $user_OS);
				$session = session_id();
				$userid = $this->id;
				$user_agent = $_SERVER['HTTP_USER_AGENT'];
				$user_OS = $browser->getPlatform() .' '. $browser->getPlatformVersion(true);

				/*
				$browser->setUserAgent($user_agent);

		        echo $browser->getName();
		        echo $browser->getVersion();
		        echo $browser->getPlatform();
		        echo $browser->getPlatformVersion(true);
        		*/

				try{
					$stmt->execute();
				}
				catch(Exception $e){
					throw new Exception('Erro ao conectar com a base de dados: '. $e);
				}
				$stmt->close();
				$conn->disconnect($conn->link);
				return TRUE;
			}

		}
	}

	public function regenerateSession($userid): bool{
		global $conn;
		$conn->link = $conn->connect();

		if($stmt = $conn->link->prepare("UPDATE users_sessions SET users_sessions.session_id = ? WHERE users_sessions.session_id = ? AND users_sessions.user_id = ?")){
			$stmt->bind_param('ssi', $newSession, $actualSession, $userid);
			$actualSession = session_id();
			session_regenerate_id();
			$newSession = session_id();

			try{
				$stmt->execute();
			}
			catch(Exception $e){
				throw new Exception('Erro ao conectar com a base de dados: '. $e);
			}
			$stmt->close();
			$conn->disconnect($conn->link);
			return TRUE;
		}
		return FALSE;
	}

	public function sessionLogin(): bool{
		global $conn;
		$conn->link = $conn->connect();

		if(session_status() == PHP_SESSION_ACTIVE){
			if($stmt = $conn->link->prepare("SELECT * FROM users_sessions, users WHERE (users_sessions.session_id = ?) AND (users_sessions.login_time >= (NOW() - INTERVAL 7 DAY)) AND (users_sessions.user_id = users.id) AND (users.active = 1)")){
				$stmt->bind_param('s', $session);
				$session = session_id();

				try{
					$stmt->execute();
					$result = $stmt->get_result();
					$data = $result->fetch_all(MYSQLI_ASSOC);
				}
				catch(Exception $e){
					throw new Exception('Erro ao conectar com a base de dados: '. $e);
				}
				if($result->num_rows > 0){
					foreach ($data as $row) {
				        //Loop through results here
				    }
					
					$stmt->bind_result($db_account_id, $db_account_userbame);
					$stmt->fetch();

					$this->id = $row['id'];
					$this->username = $row['username'];
					$this->authenticated = TRUE;
					$this->updateTime = $_SESSION['updateTime'];

					$differenceBetweenTimes = (strtotime('NOW') - $this->updateTime);
					$minutesToUpdate = $differenceBetweenTimes / 60;

					echo $minutesToUpdate.'<br>';
					echo session_id();

					if($minutesToUpdate > 30){
						//Atualiza o id da sessão a cada X minutos
						if(!$this->regenerateSession($this->id)){
							$this->logout();
						}
						$_SESSION['updateTime'] = strtotime('NOW');
					}					
					return TRUE;
				} else if($this->getFileName() == 'cpainel.php'){
					//Não redireciona, o usuário já está na página inicial
				} else{
					$this->logout();
				}
			}
		}
		return FALSE;
	}

	public function getFileName(): string {
		$arr = $_SERVER['SCRIPT_NAME'];
		$arr = explode('/', $arr);
		$arr_max = count($arr);
		array_splice($arr,0,$arr_max-1);
		
		return $arr[0];
	}

	public function isAuthenticated(): bool {
		return $this->authenticated;
	}

	public function logout(){
		global $conn;
		$conn->link = $conn->connect();

		if(is_null($this->id) && ($this->getFileName() == 'cpainel.php')){
			return;
		}

		$this->id = NULL;
		$this->username = NULL;
		$this->authenticated = FALSE;

		if($stmt = $conn->link->prepare("SELECT * users_sessions WHERE session_id = ?")){
			$stmt->bind_param('s', $session_id);
			$session_id = session_id();

			try{
				$stmt->execute();
				$stmt->store_result();
			}
			catch(Exception $e){					
				throw new Exception('Erro ao conectar com a base de dados: '. $e);
			}
			if($stmt->num_rows == 0){
				session_unset();
				session_destroy();

				header('Location: ./cpainel');
			}

		} else if(session_status() == PHP_SESSION_ACTIVE){
			if($stmt = $conn->link->prepare("DELETE FROM users_sessions WHERE session_id = ?")){
				$stmt->bind_param('s', $session_id);
				$session_id = session_id();

				try{
					$stmt->execute();
				}
				catch(Exception $e){					
					throw new Exception('Erro ao conectar com a base de dados: '. $e);
				}
				session_unset();
				session_destroy();

				header('Location: ./cpainel');
			}
		}
	}

	public function closeOtherSessions(){
		global $conn;		
		$conn->link = $conn->connect();

		if(is_null($this->id)){
			return;
		}

		if(session_status() == PHP_SESSION_ACTIVE){
			if($stmt = $conn->link->prepare("DELETE FROM users_sessions WHERE session_id != ? AND user_id = ?")){
				$stmt->bind_parm('si', $session_id, $user_id);
				$session_id = session_id();
				$user_id = $this->id;

				try{
					$stmt->execute();
				}
				catch(Exception $e){					
					throw new Exception('Erro ao conectar com a base de dados: '. $e);
				}
				session_unset();
				session_destroy();
			}
		}
	}

	public function addUser(string $username, string $name, string $email, string $password, string $permissions): bool {
		global $conn;
		$conn->link = $conn->connect();

		if($stmt = $conn->link->prepare("INSERT INTO users (username, name, email, password, permissions) VALUES (?, ?, ?, ?, ?)")){
			$stmt->bind_param('ssssi', $username, $name, $email, $hash, $permissions);

			$username = stripslashes($username);
			$username = mysqli_real_escape_string($conn->link, $username);

			$name = stripslashes($name);
			$name = mysqli_real_escape_string($conn->link, $name);

			$email = stripslashes($email);
			$email = mysqli_real_escape_string($conn->link, $email);

			$password = stripslashes($password);
			$password = mysqli_real_escape_string($conn->link, $password);
			$hash = password_hash($password, PASSWORD_DEFAULT);

			$permissions = stripslashes($permissions);
			$permissions = mysqli_real_escape_string($conn->link, $permissions);

			if(!$this->isNameValid($username)){
				throw new Exception(INVALID_USERNAME);
			}
			if(!$this->userExists($username)){
				throw new Exception(USERNAME_EXISTS);
			}
			if(!$this->isPasswordValid($password)){
				throw new Exception(INVALID_PASSWORD);
			}

			try{
				$stmt->execute();
			}
			catch(Exception $e){
				throw new Exception('Erro ao conectar com a base de dados: '. $e);
			}
			$stmt->close();
			$conn->disconnect($conn->link);
			return TRUE;
		}
	}

	public function isNameValid(string $username): bool {
		/* Initialize the return variable */
		$valid = TRUE;
		
		/* Length must be between 8 and 16 chars */
		$len = mb_strlen($username);
		
		if (($len < 8) || ($len > 16))
		{
			$valid = FALSE;
		}
		
		return $valid;
	}

	public function isPasswordValid(string $password): bool {
		/* Initialize the return variable */
		$valid = TRUE;
		
		/* Length must be between 8 and 16 chars */
		$len = mb_strlen($password);
		
		if (($len < 8) || ($len > 16))
		{
			$valid = FALSE;
		}
		
		return $valid;
	}

	public function userExists(string $username): bool {
		global $conn;
		$conn->link = $conn->connect();

		$isValid = TRUE;

		if($stmt = $conn->link->prepare("SELECT username FROM users WHERE username = ?")){
			$stmt->bind_param('s', $username);

			try{
				$stmt->execute();
				$stmt->store_result();
			}
			catch(Exception $e){
				throw new Exception('Erro ao conectar com a base de dados: '. $e);
			}

			if($stmt->num_rows > 0){
				$isValid = FALSE;
			}
			return $isValid;
		}
	}
}
$account = new _account();
?>