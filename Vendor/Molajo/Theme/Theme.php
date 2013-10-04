<?php
/**
 * Theme Service
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Theme;

use Molajo\Theme\Api\ThemeInterface;
use Molajo\Theme\Exception\ThemeException;

/**
 * The Theme Service passes the information retrieved in Route to the Content Helper
 * which retrieves additional information about the request, including the Theme,
 * Page, Template, and Wrap Views associated with the primary request.
 *
 * Next, the Theme Includer passes the request to the Mvc which renders the Theme Index.php file.
 * The output from that process is used as initial input to the parsing process in this class
 * which parses rendered output for <include type=value/> statements.
 *
 * Each include statement is processed by its associated Includer class in order to assemble the
 * parameter values needed by the Mvc to render the output. After rendering the Mvc passes the
 * rendered output back to the Includer, which passes it back to this class.
 *
 * Once returned, the rendered output is again parsed for possible new <include type=value/> statements.
 * This recursive rendering and parsing process continues until no more includes are found.
 *
 * Once complete, the Theme Service passes the rendered output back to the Application Front
 * Controller class, which sends the results as an HTTP Response back to the requester, thus
 * concluding the request to response task.
 *
 * The Theme Service schedules onBeforeParse, onBeforeParseHead, and onAfterParse Events.
 *
 * @author    Amy Stephen
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class Theme implements ThemeInterface
{
    /**
     * System defined order for processing includes
     *
     * @var    array
     * @since  1.0
     */
    protected $sequence = array();

    /**
     * Final include types
     *
     * @var    array
     * @since  1.0
     */
    protected $final = array();

    /**
     * Exclude from parsing
     *
     * @var    array
     * @since  1.0
     */
    protected $exclude_until_final = array();

    /**
     * Final processing for includes
     *
     * @var    boolean
     * @since  1.0
     */
    protected $final_indicator = false;

    /**
     * Parameters
     *
     * @var    array
     * @since  1.0
     */
    protected $parameters;

    /**
     * Dispatcher Instance
     *
     * @var    object
     * @since  1.0
     */
    protected $dispatcher;

    /**
     * Include Statements discovered during parsing
     *
     * @var    array
     * @since  1.0
     */
    protected $include_statements = array();

    /**
     * Accumulated rendered output
     *
     * @var    array
     * @since  1.0
     */
    protected $rendered_output = null;

    /**
     * Class Constructor
     *
     * @param   array $options
     *
     * @since   1.0
     */
    public function __construct(
        array $sequence,
        array $final,
        array $exclude_until_final,
        $parameters,
        $event
    ) {
        $this->sequence            = $sequence;
        $this->final               = $final;
        $this->exclude_until_final = $exclude_until_final;
        $this->parameters          = $parameters;
        $this->event               = $event;

        foreach ($this->parse_sequence as $next) {
            $this->sequence[] = (string)$next;
        }

        foreach ($this->parse_final as $next) {
            $sequence      = (string)$next;
            $this->final[] = (string)$next;

            if (stripos($sequence, ':')) {
                $include_name = substr($sequence, 0, strpos($sequence, ':'));
            } else {
                $include_name = $sequence;
            }

            $this->exclude_until_final[] = $include_name;

        }

        /** Theme Include */
        $cache = $this->getIncluderClass('Theme', 'Theme', array());
        if ($cache === true) {
            return $this->rendered_output;
        }

        $this->final_indicator = false;
    }

    /**
     * Invoke Theme Includer to load page metadata, and theme language and media resources
     *
     * Render Theme, parse output for <include type=value/> statements, pass to
     *  include renderer, continuing until no more <include type=value/> statements are found
     *
     * @param array $parameters               key value pairs
     * @param array $parameter_property_array valid parameter properties from route
     *
     * @return string
     * @since   1.0
     */
    public function renderLoop(
        $rendered_output
    ) {



        return $rendered_output;
    }


    /**
     * Parses the rendered output, looking for <include type=value/> statements.
     *
     * Note: Attribute pairs may NOT contain spaces. Escape, if needed: ex. value=This&nbsp;thing
     *  To include multiple values, separate with a comma: ex. class=one,two,three
     *
     * @return  array
     * @since   1.0
     */
    protected function getIncludeRequests()
    {
        $matches                  = array();
        $this->include_statements = array();
        $i                        = 0;

        preg_match_all('#<include(.*)\/>#iU', $this->rendered_output, $matches);

        $skipped_final_include_type = false;

        if (count($matches) == 0) {
            return;
        }

        foreach ($matches[1] as $includeStatement) {
            $parts = array();
            $temp  = explode(' ', $includeStatement);
            if (count($temp) > 0) {
                foreach ($temp as $item) {
                    if (trim($item) == '') {
                    } else {
                        $parts[] = $item;
                    }
                }
            }

            $countAttributes = 0;

            if (count($parts) > 0) {

                $include_type = '';

                foreach ($parts as $part) {

                    /** 1st part is the Includer Command */
                    if ($include_type == '') {
                        $include_type = $part;

                        /** Exclude the final include types (will be empty during document head processing) */
                        if (in_array($part, $this->exclude_until_final)) {
                            $skipped_final_include_type = true;
                        } else {
                            $this->include_statements[$i]['name']    = $include_type;
                            $this->include_statements[$i]['replace'] = $includeStatement;
                            $skipped_final_include_type              = false;
                        }
                    } elseif ($skipped_final_include_type === false) {

                        /** Includer Attributes */
                        $attributes = str_replace('"', '', $part);

                        if (trim($attributes) == '') {
                        } else {

                            /** Associative array of attributes */
                            $pair = explode('=', $attributes);

                            $countAttributes ++;

                            $this->include_statements[$i]['attributes'][$pair[0]] = $pair[1];
                        }
                    }
                }

                if ($skipped_final_include_type === false) {

                    /** Add empty array entry when no attributes */
                    if ($countAttributes == 0) {
                        $this->include_statements[$i]['attributes'] = array();
                    }

                    /** Increment count for next */
                    $i ++;
                }
            }
        }

        ob_start();
        echo 'Theme Service: processIncludeRequestsRequests found the following includes:<br />';
        foreach ($this->include_statements as $request) {
            echo $request['replace'] . '<br />';
        }
        $includeDisplay = ob_end_clean();

        $this->profiler_instance->set('message', $includeDisplay, 'Rendering');

        return;
    }

    /**
     * Instantiate Theme Includer class and pass in attributes for rendering of include
     *  and replaces the <include type=value/> with output results in $this->rendered_output
     *
     * @return  $this
     * @since   1.0
     */
    protected function processIncludeRequests()
    {
        $replace = array();
        $with    = array();

        foreach ($this->sequence as $sequence) {

            if (stripos($sequence, ':')) {
                $include_name = substr($sequence, 0, strpos($sequence, ':'));
                $include_type = substr($sequence, strpos($sequence, ':') + 1, 999);
            } else {
                $include_name = $sequence;
                $include_type = $sequence;
            }

            for ($i = 0; $i < count($this->include_statements); $i ++) {

                $parsed = $this->include_statements[$i];

                if ($include_name == $parsed['name']) {

                    if (isset($parsed['attributes'])) {
                        $attributes = $parsed['attributes'];
                    } else {
                        $attributes = array();
                    }

                    $replace[] = "<include" . $parsed['replace'] . "/>";

                    $this->set('include_name', $include_name);
                    $this->set('include_type', $include_type);
                    $this->set('tag', $this->tag);
                    $this->set('parameters', $$this->parameters);

                    $output = $this->getIncluderClass($include_type, $include_name, $attributes);

                    $with[] = $output;
                }
            }
        }

        $this->rendered_output = str_replace($replace, $with, $this->rendered_output);

        return $this;
    }

    /**
     * Pass control to Includer class to render <include type=value/>
     *
     * @param   $include_type
     * @param   $include_name
     * @param   $attributes
     *
     * @return  string
     * @since   1.0
     * @throws  ThemeException
     */
    protected function getIncluderClass($include_type, $include_name, $attributes)
    {
        if (defined(PROFILER_ON)) {
            $this->profiler_instance->set(
                'message',
                'Theme Service: getIncluderClass ' .
                ' include_type: ' . $include_type .
                ' include_name: ' . $include_name .
                ' attributes: ' . implode(' ', $attributes) .
                'Rendering'
            );
        }

        $class = ucfirst(strtolower($include_type)) . 'Includer';

        if (class_exists($class)) {
            $rc = new $class ($this->parameter_property_array,
                $this->parameters, $include_type, $include_name, $attributes);
        } else {
            throw new ThemeException
            ('Theme Service: Includer Failed Instantiating Class' . $class);
        }

        $rendered_output = $rc->process();
        $rendered_output = trim($rendered_output);

        if (defined(PROFILER_ON)) {
            $this->profiler_instance->set('message', 'Theme Service: Rendered ' . $rendered_output, 'Rendering', 1);
        }

        return $rendered_output;
    }
}
