<?php
/**
 * Jaeger
 *
 * @copyright	Copyright (c) 2015-2016, mithra62
 * @link		http://jaeger-app.com
 * @version		1.0
 * @filesource 	./Validate/Rules/Gcs/Buckets/Writable.php
 */
namespace JaegerApp\Validate\Rules\Gcs\Buckets;

use JaegerApp\Remote\Gcs as m62Gcs;
use JaegerApp\Validate\AbstractRule;
use JaegerApp\Remote\Local;
use JaegerApp\Remote;

/**
 * Jaeger - Directory Validation Rule
 *
 * Validates that a given input is a directory
 *
 * @package Validate\Rules\Gcs\Buckets
 * @author Eric Lamb <eric@mithra62.com>
 */
class Writable extends AbstractRule
{

    /**
     * The shortname of the rule
     * 
     * @var string
     */
    protected $name = 'gcs_bucket_writable';

    /**
     * The error message template
     * 
     * @var string
     */
    protected $error_message = 'Your bucket doesn\'t appear to be writable...';

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
            
            $local = new Remote(new Local(dirname($this->getTestFilePath())));
            $client = m62Gcs::getRemoteClient($params['gcs_access_key'], $params['gcs_secret_key']);
            if ($client->doesBucketExist($params['gcs_bucket'])) {
                $contents = $local->read($this->test_file);
                $filesystem = new Remote(new m62Gcs($client, $params['gcs_bucket']));
                
                if ($filesystem->has($this->test_file)) {
                    $filesystem->delete($this->test_file);
                } else {
                    if ($filesystem->write($this->test_file, $contents)) {
                        $filesystem->delete($this->test_file);
                    }
                }
                
                return true;
            }
        } catch (\Exception $e) {
            return false;
        }
    }
}

$rule = new Writable;
\JaegerApp\Validate::addrule($rule->getName(), array($rule, 'validate'), $rule->getErrorMessage());
