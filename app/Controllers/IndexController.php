<?php
/**
 * This file is part of Swoft.
 *
 * @link https://swoft.org
 * @document https://doc.swoft.org
 * @contact group@swoft.org
 * @license https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace App\Controllers;

use Swoft\App;
use Swoft\Http\Server\Bean\Annotation\Controller;
use Swoft\Http\Server\Bean\Annotation\RequestMapping;
use Swoft\Log\Log;
use Swoft\View\Bean\Annotation\View;
use Swoft\Contract\Arrayable;
use Swoft\Http\Server\Exception\BadRequestException;
use Swoft\Http\Message\Server\Response;

/**
 * Class IndexController
 * @Controller()
 */
class IndexController
{

    /**
     * @RequestMapping("/")
     * @View(template="index/index")
     * @return array
     */
    public function index(): array
    {
        $name = 'Swoft';
        $notes = [
            'New Generation of PHP Framework',
            'High Performance, Coroutine and Full Stack'
        ];
        $links = [
            [
                'name' => 'Home',
                'link' => 'http://www.swoft.org',
            ],
            [
                'name' => 'Documentation',
                'link' => 'http://doc.swoft.org',
            ],
            [
                'name' => 'Case',
                'link' => 'http://swoft.org/case',
            ],
            [
                'name' => 'Issue',
                'link' => 'https://github.com/swoft-cloud/swoft/issues',
            ],
            [
                'name' => 'GitHub',
                'link' => 'https://github.com/swoft-cloud/swoft',
            ],
        ];
        // 返回一个 array 或 Arrayable 对象，Response 将根据 Request Header 的 Accept 来返回数据，目前支持 View, Json, Raw
        return compact('name', 'notes', 'links');
    }

    /**
     * show view by view function
     */
    public function templateView(): Response
    {
        $name = 'Swoft View';
        $notes = [
            'New Generation of PHP Framework',
            'High Performance, Coroutine and Full Stack'
        ];
        $links = [
            [
                'name' => 'Home',
                'link' => 'http://www.swoft.org',
            ],
            [
                'name' => 'Documentation',
                'link' => 'http://doc.swoft.org',
            ],
            [
                'name' => 'Case',
                'link' => 'http://swoft.org/case',
            ],
            [
                'name' => 'Issue',
                'link' => 'https://github.com/swoft-cloud/swoft/issues',
            ],
            [
                'name' => 'GitHub',
                'link' => 'https://github.com/swoft-cloud/swoft',
            ],
        ];
        $data = compact('name', 'notes', 'links');

        return view('index/index', $data);
    }

    /**
     * @RequestMapping()
     * @View(template="index/index")
     * @return \Swoft\Contract\Arrayable|__anonymous@836
     */
    public function arrayable(): Arrayable
    {
        return new class implements Arrayable
        {
            /**
             * @return array
             */
            public function toArray(): array
            {
                return [
                    'name'  => 'Swoft',
                    'notes' => ['New Generation of PHP Framework', 'High Performance, Coroutine and Full Stack'],
                    'links' => [
                        [
                            'name' => 'Home',
                            'link' => 'http://www.swoft.org',
                        ],
                        [
                            'name' => 'Documentation',
                            'link' => 'http://doc.swoft.org',
                        ],
                        [
                            'name' => 'Case',
                            'link' => 'http://swoft.org/case',
                        ],
                        [
                            'name' => 'Issue',
                            'link' => 'https://github.com/swoft-cloud/swoft/issues',
                        ],
                        [
                            'name' => 'GitHub',
                            'link' => 'https://github.com/swoft-cloud/swoft',
                        ],
                    ]
                ];
            }

        };
    }

    /**
     * @RequestMapping()
     * @return Response
     */
    public function absolutePath(): Response
    {
        $data = [
            'name'  => 'Swoft',
            'notes' => ['New Generation of PHP Framework', 'High Performance, Coroutine and Full Stack'],
            'links' => [
                [
                    'name' => 'Home',
                    'link' => 'http://www.swoft.org',
                ],
                [
                    'name' => 'Documentation',
                    'link' => 'http://doc.swoft.org',
                ],
                [
                    'name' => 'Case',
                    'link' => 'http://swoft.org/case',
                ],
                [
                    'name' => 'Issue',
                    'link' => 'https://github.com/swoft-cloud/swoft/issues',
                ],
                [
                    'name' => 'GitHub',
                    'link' => 'https://github.com/swoft-cloud/swoft',
                ],
            ]
        ];
        $template = 'index/index';
        return view($template, $data);
    }

    /**
     * @RequestMapping()
     * @return string
     */
    public function raw()
    {
        $name = 'Swoft';
        return $name;
    }

    public function testLog()
    {
        App::trace('this is app trace');
        Log::trace('this is log trace');
        App::error('this is log error');
        Log::trace('this is log error');
        return ['log'];
    }

    /**
     * @RequestMapping()
     * @throws \Swoft\Http\Server\Exception\BadRequestException
     */
    public function exception()
    {
        throw new BadRequestException('bad request exception');
    }

    /**
     * @RequestMapping()
     * @param Response $response
     * @return Response
     */
    public function redirect(Response $response): Response
    {
        return $response->redirect('/');
    }
}
