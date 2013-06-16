<?php

class messagesTest extends WebTestCase
{
	public $fixtures=array(
		'messages'=>'messages',
	);

	public function testShow()
	{
		$this->open('?r=messages/view&id=1');
	}

	public function testCreate()
	{
		$this->open('?r=messages/create');
	}

	public function testUpdate()
	{
		$this->open('?r=messages/update&id=1');
	}

	public function testDelete()
	{
		$this->open('?r=messages/view&id=1');
	}

	public function testList()
	{
		$this->open('?r=messages/index');
	}

	public function testAdmin()
	{
		$this->open('?r=messages/admin');
	}
}
