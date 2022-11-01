<?php


/**
 */
class VKApiTooManyException extends VKApiException {

	/**
	 * VKApiTooManyException constructor.
	 *
	 * @param VkApiError $error
	 */
	public function __construct(VkApiError $error) {
		parent::__construct(6, 'Too many requests per second', $error);
	}
}
