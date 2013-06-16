<?php

class T1_1Test extends WebTestCase
{
	public $fixtures=array(
		't1_1s'=>'T1_1',
	);

	public function testShow()
	{
		$this->open('?r=t1_1/view&id=1');
	}

	public function testCreate()
	{
		$this->open('?r=t1_1/create');
	}

	public function testUpdate()
	{
		$this->open('?r=t1_1/update&id=1');
	}

	public function testDelete()
	{
		$this->open('?r=t1_1/view&id=1');
	}

	public function testList()
	{
		$this->open('?r=t1_1/index');
	}

	public function testAdmin()
	{
		$this->open('?r=t1_1/admin');
	}
}
