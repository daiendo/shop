<?php
namespace app\admin\controller;
use think\Request;

class Index extends Error
{
    public function indexApi(Request $request)
    {
        return [200,'qwe'];
    }

    public function hello($name = 'ThinkPHP5')
    {
        return 'hello,' . $name;
    }
}
