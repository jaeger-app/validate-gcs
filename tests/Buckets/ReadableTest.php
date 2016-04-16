<?php
/**
 * Jaeger
 *
 * @copyright	Copyright (c) 2015-2016, mithra62
 * @link		http://jaeger-app.com
 * @version		1.0
 * @filesource 	./tests/Buckets/ExistsTest.php
 */
namespace JaegerApp\tests\Buckets;

use JaegerApp\Validate;
use JaegerApp\Validate\Rules\Gcs\Buckets\Readable;

/**
 * Jaeger - Valiate object Unit Tests
 *
 * Contains all the unit tests for the \mithra62\Valiate object
 *
 * @package Jaeger\Tests
 * @author Eric Lamb <eric@mithra62.com>
 */
class ReadableTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Tests the name of the rule
     */
    public function testName()
    {
        $dir = new Readable();
        $this->assertEquals($dir->getName(), 'gcs_bucket_readable');
    }

    /**
     * Tests that a file can be determined false
     */
    public function testRuleFail()
    {
        $val = new Validate();
        $creds = $this->getGcsCreds();
        $creds['gcs_bucket'] = 'ffdsafdsafdsafd';
        $val->rule('gcs_bucket_readable', 'connection_field', $creds)->val(array(
            'connection_field' => __FILE__
        ));
        $this->assertTrue($val->hasErrors());
    }

    /**
     * Tests that a directory can be determined true
     */
    public function testRuleSuccess()
    {
        $val = new Validate();
        $val->rule('gcs_bucket_readable', 'connection_field', $this->getGcsCreds())
            ->val(array(
            'connection_field' => 'Foo'
        ));
        $this->assertFALSE($val->hasErrors());
    }

    /**
     * The Google Cloud Storage Test Credentials
     */
    protected function getGcsCreds()
    {
        return include __DIR__. '/../data/gcscreds.config.php';
    }
}