<?php
/**
 * Jaeger
 *
 * @copyright	Copyright (c) 2015-2016, mithra62
 * @link		http://jaeger-app.com
 * @version		1.0
 * @filesource 	./Validate/Rules/Gcs/Connect.php
 */
namespace JaegerApp\Validate\Rules\Gcs;

use JaegerApp\Remote\Gcs as m62Gcs;
use JaegerApp\Validate\AbstractRule;

/**
 * Jaeger - Google Cloud Storage Connection Validation Rule
 *
 * Validates that a given connection detail set can connect to Google Cloud Storage
 *
 * @package Validate\Rules\Gcs
 * @author Eric Lamb <eric@mithra62.com>
 */
class Connect extends AbstractRule
{

    /**
     * The Rule shortname
     * 
     * @var string
     */
    protected $name = 'gcs_connect';

    /**
     * The error template
     * 
     * @var string
     */
    protected $error_message = 'Can\'t connect to {field}';

    /**
     * (non-PHPdoc)
     * 
     * @ignore
     *
     * @see \mithra62\Validate\RuleInterface::validate()
     */
    public function validate($field, $input, array $params = array())
    {
        try {
            if ($input == '' || empty($params['0'])) {
                return false;
            }
            $params = $params['0'];
            if (empty($params['gcs_access_key']) || empty($params['gcs_secret_key'])) {
                return false;
            }
            
            $client = m62Gcs::getRemoteClient($params['gcs_access_key'], $params['gcs_secret_key']);
            $client->listBuckets();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}

$rule = new Connect;
\JaegerApp\Validate::addrule($rule->getName(), array($rule, 'validate'), $rule->getErrorMessage());
