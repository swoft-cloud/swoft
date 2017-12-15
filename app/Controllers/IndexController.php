<?php

namespace App\Controllers;

use Swoft\Bean\Annotation\Controller;
use Swoft\Bean\Annotation\RequestMapping;
use Swoft\Bean\Annotation\View;
use Swoft\Contract\Arrayable;
use Swoft\Exception\Http\BadRequestException;
use Swoft\Web\Response;

/**
 * Class IndexController
 * @Controller()
 *
 * @package App\Controllers
 */
class IndexController
{

    /**
     * @RequestMapping()
     * @View(template="index/index")
     * @return array
     */
    public function actionIndex()
    {
        $name = 'Swoft';
        $notes = [
            'New Generation of PHP Framework',
            'Hign Performance, Coroutine and Full Stack'
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
     * @RequestMapping()
     * @View(template="index/index")
     * @return \Swoft\Contract\Arrayable|__anonymous@836
     */
    public function actionArrayable()
    {
        return (new class implements Arrayable
        {
            /**
             * @return array
             */
            public function toArray(): array
            {
                return [
                    'name' => 'Swoft',
                    'notes' => ['New Generation of PHP Framework', 'Hign Performance, Coroutine and Full Stack'],
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

        });
    }

    /**
     * @RequestMapping()
     * @param \Swoft\Web\Response $response
     * @return Response
     */
    public function absolutePath(Response $response)
    {
        $template = '@res/views/index/index.php';
        return $response->view([
            'name' => 'Swoft',
            'notes' => ['New Generation of PHP Framework', 'Hign Performance, Coroutine and Full Stack'],
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
        ], $template);
    }

    /**
     * @RequestMapping()
     * @return string
     */
    public function actionRaw()
    {
        $name = 'Swoft';
        return $name;
    }

    /**
     * @RequestMapping()
     */
    public function actionException()
    {
        throw new BadRequestException();
    }

    /**
     * @RequestMapping()
     * @param \Swoft\Web\Response $response
     * @return \Swoft\Base\Response
     */
    public function actionRedirect(Response $response)
    {
        return $response->redirect('/');
    }

}
