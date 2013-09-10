<?php
/**
 * Mail class
 *
 * Author:	Benoit Zuckschwerdt
 * Date:	19 September 2012
 *
 * Version 1.0
 *
 * Changes:
 * 	+ {...}
 */

class Kust_Mail {
	private $subject;
	private $targets = array();
	private $message;


	/**
	 * Constructor
	 * @param array $config
	 */
	public function __construct($config) {
		$this->configure($config);
	}


	/**
	 * Configure
	 * @param array $c
	 */
	public function configure($c) {
		$this->subject = (!empty($c['subject']) ? $c['subject'] : '');
		$this->targets = (!empty($c['targets']) ? $c['targets'] : '');
		$this->message = (!empty($c['message']) ? $c['message'] : '');
	}


	/**
	 * Send mails to targets
	 * @return boolean
	 */
	public function send() {
		# For multiple target
		if(is_array($this->targets)) {
			foreach($this->targets as $to) {
				$result = mail($to, $this->subject, $this->message);
				if(!$result) break;
			}

		# For just one target
		} else
			$result = mail($this->targets, $this->subject, $this->message);

		return $result;
	}
}

?>
