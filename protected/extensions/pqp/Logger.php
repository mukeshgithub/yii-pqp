<?php
class Logger extends CLogger{

	const LEVEL_MEMORY='memory';

	/**
	 * @var array log messages
	 */
	private $_logs=array();

	/**
	 * Logs a message.
	 * Messages logged by this method may be retrieved back via {@link getLogs}.
	 * @param string message to be logged
	 * @param string level of the message (e.g. 'Trace', 'Warning', 'Error'). It is case-insensitive.
	 * @param string category of the message (e.g. 'system.web'). It is case-insensitive.
	 * @see getLogs
	 */
	public function log($message,$level='info',$category='application')
	{
		$this->_logs[]=array($message,$level,$category,microtime(true));
		$this->_logCount++;
		if($this->autoFlush>0 && $this->_logCount>=$this->autoFlush){
			$this->flush();
		}
	}
}