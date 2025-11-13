<?php

declare( strict_types = 1 );

namespace Ocolin\Calix\Axos\Tests;

class SubscriberTest extends TestTest
{
    public function testCreateBad() : void
    {
        $output = self::$api->call(
            path: '/ems/subscriber',
            method: 'POST',
            body: []
        );
        self::assertIsArray( actual: $output );
        self::assertIsObject( actual: $output[0] );
        self::assertObjectHasProperty( propertyName: 'resultCode', object: $output[0] );
        self::assertObjectHasProperty( propertyName: 'userMessage', object: $output[0] );
        self::assertEquals( expected: 'COM.5504', actual: $output[0]->resultCode );
        //print_r( $output );
    }

    public function testCreateGood() : void
    {
        $output = self::$api->call(
            path: '/ems/subscriber',
            method: 'POST',
            body: [
                'name' => 'PHPUnit test',
                'customId' => 777,
                'type' => 'Residential',
                'orgId' => 'Calix',
                'locations' => [
                    [
                        'description' => 'PHPUnit test',
                        'primary' => true,
                        'address' => [
                            'streetLine1' => '877 Cedar St.',
                            'city'        => 'Santa Cruz',
                            'state'       => 'CA',
                            'zip'         => '95060',
                            'country'     => 'United States'
                        ],
                        'contacts' => [
                            'firstName'   => 'TestFirst',
                            'lastName'     => 'TestLast',
                            'phone'       => '555-1212',
                            'email'       => 'null@cruzio.com',
                            'primary'     => true,
                        ]
                    ]
                ]
            ]
        );
        self::assertIsArray( actual: $output );
        self::assertIsObject( actual: $output[0] );
        self::assertObjectHasProperty( propertyName: 'resultCode', object: $output[0] );
        self::assertObjectHasProperty( propertyName: 'userMessage', object: $output[0] );
        self::assertEquals( expected: 'OBJ.5101', actual: $output[0]->resultCode );
        //print_r( $output );
    }


    public function testGetGood() : void
    {
        $output = self::$api->call(
            path: '/ems/subscriber/org/{org-id}/account/{account-name}',
            query: [
                'org-id' => 'Calix',
                'account-name' => 777,
            ]
        );
        self::assertIsObject( actual: $output );
        self::assertObjectHasProperty( propertyName: 'name', object: $output );
        self::assertObjectHasProperty( propertyName: 'customId', object: $output );
        self::assertEquals( expected: '777', actual: $output->customId );
        //print_r( $output );
    }

    public function testGetBad() : void
    {
        $output = self::$api->call(
            path: '/ems/subscriber/org/{org-id}/account/{account-name}',
            query: [
                'org-id' => 'Calix',
                'account-name' => 888,
            ]
        );
        self::assertIsObject( actual: $output );
        self::assertObjectHasProperty( propertyName: 'resultCode', object: $output );
        self::assertObjectHasProperty( propertyName: 'userMessage', object: $output );
        self::assertEquals( expected: 'COM.1003', actual: $output->resultCode );
        //print_r( $output );
    }


    public function testUpdateGood() : void
    {
        $output = self::$api->call(
            path: '/ems/subscriber/org/{org-id}/account/{account-name}',
            method: 'PUT',
            query: [
                'org-id' => 'Calix',
                'account-name' => 777,
            ],
            body: [
                'name' => 'PHPUnit update',
                'orgId' => 'Calix',
                'customId' => 777,
            ]
        );
        self::assertIsArray( actual: $output );
        self::assertIsObject( actual: $output[0] );
        self::assertObjectHasProperty( propertyName: 'resultCode', object: $output[0] );
        self::assertObjectHasProperty( propertyName: 'userMessage', object: $output[0] );
        self::assertEquals( expected: 'OBJ.5102', actual: $output[0]->resultCode );
        //print_r( $output );
    }


    public function testDeleteGood() : void
    {
        $output = self::$api->call(
            path: '/ems/subscriber/org/{org-id}/account/{account-name}',
            method: 'DELETE',
            query: [
                'org-id' => 'Calix',
                'account-name' => 777,
            ]
        );
        self::assertIsArray( actual: $output );
        self::assertIsObject( actual: $output[0] );
        self::assertObjectHasProperty( propertyName: 'resultCode', object: $output[0] );
        self::assertObjectHasProperty( propertyName: 'userMessage', object: $output[0] );
        self::assertEquals( expected: 'OBJ.5103', actual: $output[0]->resultCode );
        //print_r( $output );
    }

    public function testDeleteBad() : void
    {
        $output = self::$api->call(
            path: '/ems/subscriber/org/{org-id}/account/{account-name}',
            method: 'DELETE',
            query: [
                'org-id' => 'Calix',
                'account-name' => 777,
            ]
        );
        self::assertIsArray( actual: $output );
        self::assertIsObject( actual: $output[0] );
        self::assertObjectHasProperty( propertyName: 'resultCode', object: $output[0] );
        self::assertObjectHasProperty( propertyName: 'userMessage', object: $output[0] );
        self::assertEquals( expected: 'COM.1002', actual: $output[0]->resultCode );
        //print_r( $output );
    }
}

///ems/subscriber/org/{org-id}/account/{account-name}