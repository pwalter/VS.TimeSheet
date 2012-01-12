<?php
namespace VS\TimeSheet\ViewHelpers;

use TYPO3\FLOW3\Annotations as FLOW3;

class LessViewHelper extends \TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
     * @FLOW3\Inject
	 * @var \TYPO3\FLOW3\Resource\Publishing\ResourcePublisher
	 */
	protected $resourcePublisher;

	/**
	 * Render the URI to the resource. The filename is used from child content.
	 *
	 * @param string $path The path and filename of the resource (relative to Public resource directory of the package)
	 * @param string $package Target package key. If not set, the current package key will be used
	 * @param \TYPO3\FLOW3\Resource\Resource $resource If specified, this resource object is used instead of the path and package information
	 * @param string $uri A resource URI, a relative / absolute path or URL
	 * @return string The absolute URI to the resource
	 */
	public function render($path = NULL, $package = NULL, $resource = NULL, $uri = NULL) {
		if ($uri !== NULL) {
			if (preg_match('#resource://([^/]*)/Public/(.*)#', $uri, $matches) > 0) {
				$package = $matches[1];
				$path = $matches[2];
			} else {
                \TYPO3\FLOW3\var_dump($uri);
				return $uri;
			}
		}
		if ($resource === NULL) {
			if ($path === NULL) {
				return '!!! No path specified in uri.resource view helper !!!';
			}
			$uri = $this->resourcePublisher->getStaticResourcesWebBaseUri() . 'Packages/' . ($package === NULL ? $this->controllerContext->getRequest()->getControllerPackageKey() : $package ). '/' . $path;
		} else {
			$uri = $this->resourcePublisher->getPersistentResourceWebUri($resource);
			if ($uri === FALSE) {
				$uri = $this->resourcePublisher->getStaticResourcesWebBaseUri() . 'BrokenResource';
			}
		}
        \TYPO3\FLOW3\var_dump($uri);
		return $uri;
	}
}

?>
