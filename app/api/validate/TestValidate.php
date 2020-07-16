<?php


	namespace app\api\validate;

	use think\Validate;
	class TestValidate extends Validate
	{
		
		protected $rule = [
			'page' => 'require|integer',
            'limit' => 'require|integer',
		];
		protected $message  =   [
	        'page.require' => '请传入页码',
	        'page.integer'     => '请传入正确的页码',
	        'limit.require'   => '请传入每页条数',
	        'limit.integer'  => '请传入正确的每页条数',
	    ];
	}