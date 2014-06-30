<?php namespace Xinax\LaravelLatch;

use ElevenPaths\Latch\Latch,
	\Config;

class LaravelLatch{

	/**
	 * Config singleton
	 * @var Array
	 */
	protected $config = null;

	/**
	 * Latch handler
	 * @var ElevenPaths\Latch
	 */
	protected $latch = null;

	/**
	 * Latch initializer
	 */
	public function __construct(){

		$this->setConfig();

		$this->latch = new Latch(
			$this->config['application-id'],
			$this->config['application-secret']
		);

		if(!is_null($this->config['proxy'])){
			$this->latch->setProxy($this->config['proxy']);
		}

	}

	/**
	 * Register a pair device code with 
	 * your application. 
	 * @param String $pairCode
	 * @return String $accountID
	 */
	public function pair($pairCode){

		$pairResponse = $this->latch->pair($pairCode);

		if($error = $pairResponse->getError()){

			$errorCode = $error->getCode();
			$message = $error->getMessage();

			throw new Exceptions\PairErrorException("Error $errorCode: $message");

		}

		return $pairResponse->getData()->accountId;

	}

	/**
	 * Return the current account latch status
	 * @param String $accountID
	 * @return ElevenPaths\LatchResponse
	 */
	public function status($accountID){

		$statusResponse = $this->latch->status($accountID);

		if($error = $statusResponse->getError()){

			$errorCode = $error->getCode();
			$message = $error->getMessage();

			throw new Exceptions\LatchErrorException("Error $errorCode: $message");

		}

		$applicationID = $this->config['application-id'];
		$operation = $statusResponse->getData()->operations->$applicationID;

  		if(!is_object($operation) || is_null($operation)){
  			throw new Exceptions\BadResponseException('No response from latch server');
  		}

  		if('on' != $operation->status){
  			throw new Exceptions\ClosedLatchException('Latch protection');	
  		} 

  		return $statusResponse;

	}

	/**
	 * Unpair an account from application
	 * @param String $accountID
	 */
	public function unpair($accountID){

		$unpairResponse = $this->latch->unpair($accountID);

		if($error = $unpairResponse->getError()){

			$errorCode = $error->getCode();
			$message = $error->getMessage();

			throw new Exceptions\LatchErrorException("Error $errorCode: $message");

		}

	}

	/**
	 * Sets the configuration array
	 * @return void
	 */
	protected function setConfig(){

		if(is_null($this->config)){
		
			$config = Config::get('laravel-latch::config');

			if(!$config || !count($config)){
				throw new RequiredConfigurationFileException(
				"You need to publish the package configuration file");
			}

			$this->config = $config;

		}

	}

}
