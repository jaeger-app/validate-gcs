<?php
/**
 * Jaeger
 *
 * @copyright	Copyright (c) 2015-2016, mithra62
 * @link		http://jaeger-app.com
 * @version		1.0
 * @filesource 	./Validate/Rules/Gcs/Buckets/Exists.php
 */
namespace JaegerApp\Validate\Rules\Gcs\Buckets;

use JaegerApp\Validate\AbstractRule;
use JaegerApp\Remote\Gcs as m62Gcs;

/**
 * Jaeger - Google Cloud Storage Bucket Existance Validation Rule
 *
 * Validates that a given bucket name exists in S3
 *
 * @package Validate\Rules\Gcs\Buckets
 * @author Eric Lamb <eric@mithra62.com>
 */
class Exists extends AbstractRule
{

    /**
     * The Rule shortname
     * 
     * @var string
     */
    protected $name = 'gcs_bucket_exists';

    /**
     * The error template
     * 
     * @var string
     */
    protected $error_message = 'Your bucket doesn\'t appear to exist...';

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
            if (empty($params['gcs_access_key']) || empty($params['gcs_secret_key']) || empty($params['gcs_bucket'])) {
                return false;
            }
            
            $client = m62Gcs::getRemoteClient($params['gcs_access_key'], $params['gcs_secret_key']);
            if ($client->doesBucketExist($params['gcs_bucket'])) {
                return true;
            }
        } catch (\Exception $e) {
            return false;
        }
    }
}

$rule = new Exists;
\JaegerApp\Validate::addrule($rule->getName(), array($rule, 'validate'), $rule->getErrorMessage());
