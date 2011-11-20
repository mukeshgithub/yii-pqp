<?php
Yii::import('application.extensions.pqp.classes.PhpQuickProfiler');
/**
 * @desc particletree PhpQuickPRofiler Wrapper
 * @see http://particletree.com/features/php-quick-profiler/
 * @author Asgaroth[dot]Belem[at]gmail[dot]com
 * @version 0.1
 * @since Feb 4, 2011 - 3:48:24 PM
 */
class PQProfiler extends CLogRoute {
	/**
	 * Processes log messages and sends them to specific destination.
	 * @param array list of messages.  Each array elements represents one message
	 * with the following structure:
	 * array(
	 *   [0] => message (string)
	 *   [1] => level (string)
	 *   [2] => category (string)
	 *   [3] => timestamp (float, obtained by microtime(true));
	 */
	protected function processLogs($logs)
	{
		$profileTable = array();
		$results = array();
		foreach ($logs as $log) {
			$method = 'info';
			switch ($log[1]) {
				case CLogger::LEVEL_INFO:
					$method = 'info';
					break;
				case CLogger::LEVEL_ERROR:
					$method = 'error';
					break;
				case CLogger::LEVEL_WARNING:
					$method = 'warn';
					break;
				case CLogger::LEVEL_PROFILE:
					$method = 'table';
					break;
			}

			if ($method == 'trace') {
				// FirePHP's trace method do not include labels
				$trace = str_replace(array('#{category}', '#{timestamp}', '#{message}'),
				array($log[2], date(DateTime::W3C, $log[3]), $log[0]),
				$this->traceFormat);
				FB::$method($trace);
			}else if($method == 'table'){
				$this->processProfileLog($profileTable, $results, $log);
			}else {
				$category = str_replace(array('#{category}', '#{timestamp}'),
				array($log[2], date(DateTime::W3C, $log[3])),
				$this->labelFormat);
				FB::$method($log[0], $category);
			}
		}

		$this->displayProfileTable($profileTable, $results);
	}

}