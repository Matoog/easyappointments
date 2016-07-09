<?php 

/* ----------------------------------------------------------------------------
 * Easy!Appointments - Open Source Web Scheduler
 *
 * @package     EasyAppointments
 * @author      A.Tselegidis <alextselegidis@gmail.com>
 * @copyright   Copyright (c) 2013 - 2016, Alex Tselegidis
 * @license     http://opensource.org/licenses/GPL-3.0 - GPLv3
 * @link        http://easyappointments.org
 * @since       v1.2.0
 * ---------------------------------------------------------------------------- */

namespace EA\Engine\Api\V1; 

/**
 * API v1 Response 
 *
 * Use chain-calls of the class methods to easily manipulate the provided response array. This class will
 * use directly the provided GET parameters for easier manipulation.
 *
 * Example:
 *   $formatter = new \EA\Engine\Api\V1\Formatters\Appointments;
 *   $response = new \EA\Engine\Api\V1\Response($data);
 *   $response->format($formatter)->search()->sort()->paginate()->minimize()->output();
 */
class Response {
    /**
     * Contains the response information. 
     * 
     * @var array
     */
    protected $response;

    /**
     * Class Constructor 
     * 
     * @param array $response Provide unfiltered data to be processed as an array. 
     */
    public function __construct(array $response) {
        $this->response = $response;
    }

    /**
     * Format the response entries to the API compatible structure. 
     * 
     * @param \Formatters\FormattersInterface $formatter Provide the corresponding formatter class. 
     *
     * @return \EA\Engine\Api\V1\Response
     */
    public function format(Formatters\FormattersInterface $formatter) {
        $formatter->format($this->response);

        return $this;
    }

    /**
     * Perform a response search. 
     * 
     * @return \EA\Engine\Api\V1\Response
     */
    public function search() {
        Processors\Search::process($this->response); 

        return $this;
    }

    /**
     * Perform a response sort. 
     *
     * @return \EA\Engine\Api\V1\Response
     */
    public function sort() {
        Processors\Sort::process($this->response); 

        return $this;
    }

    /**
     * Perform a response paginate. 
     *
     * @return \EA\Engine\Api\V1\Response
     */
    public function paginate() {
        Processors\Paginate::process($this->response); 

        return $this;
    }

    /**
     * Perform a response minimize. 
     *
     * @return \EA\Engine\Api\V1\Response
     */
    public function minimize() {
        Processors\Minimize::process($this->response);

        return $this;
    }

    /**
     * Output the response as a JSON with the provided status header. 
     *
     * @param \EA\Engine\Types\NonEmptyString $status Optional (null), if provided it must contain the status  
     * header value. Default string: '200 OK'.
     *
     * @return \EA\Engine\Api\V1\Response
     */
    public function output(NonEmptyString $status = null) {
        $header = $status ? $status->get() : '200 OK'; 

        header('HTTP/1.0 ' . $header); 

        echo json_encode($this->response, JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);

        return $this;
    }
}