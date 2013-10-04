<?php
/**
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Plugin\Messages;

use Molajo\Plugin\AbstractPlugin;
use Molajo\Service;


/**
 * @package     Molajo
 * @license     MIT
 * @since       1.0
 */
class MessagesPlugin extends AbstractPlugin
{
    /**
     * Prepares system messages for display
     *
     * @return boolean
     * @since   1.0
     */
    public function onBeforeParseHead()
    {
        $controller_class_namespace = $this->controller_namespace;
        $controller                 = new $controller_class_namespace();
        $controller->getModelRegistry('Dataobject', 'Messages', 1);
        $messages = $controller->getData('list');

        if (count($messages) == 0 || $messages === false) {
            $this->registry->set(
                'Messages',
                $this->get('template_view_path_node', '', 'parameters'),
                array()
            );

            return true;
        }

        $temp_query_results = array();

        foreach ($messages as $message) {

            $temp_row = new \stdClass();

            $temp_row->message = $message->message;
            $temp_row->type    = $message->type;
            $temp_row->code    = $message->code;
            $temp_row->action  = $this->registry->get('parameters', 'request_base_url_path') .
                $this->registry->get('parameters', 'request_url');

            $temp_row->class = 'alert-box';
            if ($message->type == 'Success') {
                $temp_row->heading = $this->language->translate('Success');
                $temp_row->class .= ' success';

            } elseif ($message->type == 'Warning') {
                $temp_row->heading = $this->language->translate('Warning');
                $temp_row->class .= ' warning';

            } elseif ($message->type == 'Error') {
                $temp_row->heading = $this->language->translate('Error');
                $temp_row->class .= ' alert';

            } else {
                $temp_row->heading = $this->language->translate('Information');
                $temp_row->class .= ' secondary';
            }
            $temp_query_results[] = $temp_row;
        }

        $this->registry->set(
            'Template',
            $this->get('template_view_path_node', '', 'parameters'),
            $temp_query_results
        );

        return true;
    }
}
