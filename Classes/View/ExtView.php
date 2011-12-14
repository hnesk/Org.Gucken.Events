<?php

namespace Org\Gucken\Events\View;

use TYPO3\FLOW3\Annotations as FLOW3;

/**
 * @FLOW3\Scope("prototype")
 */
class ExtView extends \TYPO3\FLOW3\MVC\View\JsonView {

    public function initializeObject() {
        $this->setConfiguration(
            array(
                'value' => array(
                    'data' => array(
                        '_descendAll' => array(
                            '_descend' => array(
                                'location' => array(),
                                'address' => array(),
                                'geo' => array(),
                            ),
                            '_exposeObjectIdentifier' => TRUE
                        )
                    )
                )
            )
        );
    }

    /**
     *
     * @param mixed $data 
     */
    public function assignResponse($data) {
        $this->assign('value', array('data' => $data, 'success' => TRUE));
    }

}

?>
