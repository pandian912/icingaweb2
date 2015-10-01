<?php
/* Icinga Web 2 | (c) 2013-2015 Icinga Development Team | GPLv2+ */

namespace Icinga\Module\Monitoring\Forms\Command\Object;

use Icinga\Module\Monitoring\Command\Object\RemoveAcknowledgementCommand;
use Icinga\Web\Notification;

/**
 * Form for removing host or service problem acknowledgements
 */
class RemoveAcknowledgementCommandForm extends ObjectsCommandForm
{
    /**
     * (non-PHPDoc)
     * @see \Zend_Form::init() For the method documentation.
     */
    public function init()
    {
        $this->setAttrib('class', 'inline');
    }

    /**
     * {@inheritdoc}
     */
    public function addSubmitButton()
    {
        $this->addElement(
            'button',
            'btn_submit',
            array(
                'class'         => 'link-button spinner',
                'decorators'    => array(
                    'ViewHelper',
                    array('HtmlTag', array('tag' => 'div', 'class' => 'control-group form-controls'))
                ),
                'escape'        => false,
                'ignore'        => true,
                'label'         => $this->getView()->icon('cancel') . $this->translatePlural(
                    'Remove problem acknowledgement',
                    'Remove problem acknowledgements',
                    count($this->objects)
                ),
                'title'         => $this->translatePlural(
                    'Remove problem acknowledgement',
                    'Remove problem acknowledgements',
                    count($this->objects)
                ),
                'type'          => 'submit'
            )
        );
        return $this;
    }

    /**
     * (non-PHPDoc)
     * @see \Icinga\Web\Form::onSuccess() For the method documentation.
     */
    public function onSuccess()
    {
        foreach ($this->objects as $object) {
            /** @var \Icinga\Module\Monitoring\Object\MonitoredObject $object */
            $removeAck = new RemoveAcknowledgementCommand();
            $removeAck->setObject($object);
            $this->getTransport($this->request)->send($removeAck);
        }
        Notification::success(mtp(
            'monitoring',
            'Removing problem acknowledgement..',
            'Removing problem acknowledgements..',
            count($this->objects)
        ));
        return true;
    }
}
