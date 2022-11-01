<?php


/**
 */
class VKApiRequestException extends VKApiException {

	/**
	 * VKApiRequestException constructor.
	 *
	 * @param VkApiError $error
	 */
	public function __construct(VkApiError $error) {
		parent::__construct(8, 'Invalid request', $error);
	}
}
