<?php

class EasyRoute extends Route {

    /**
     * @param $name
     * @return bool
     */
    public static function is( $name )
    {
        return static::currentRouteName() == $name || Request::url() == URL::to($name);
    }

    /**
     * Easy way to create controllers
     *
     * @param  string $class
     * @param  array  $routes
     * @return void
     */
    public static function controller($class, $routes)
    {
        $wheres = array('{id}' => '[0-9]+', '{title}' => '.*', '{slug}' => '.*');

        foreach ($routes as $route => $array)
        {
            $uri = $array[0];

            $types  = array('get', 'post', 'put', 'delete');
            $methods = explode(',', $array[1]);

            for ($i = 0; $i < count($methods); $i++)
            {
                $type = false;

                // If method type is defined by user.
                if(($pos = strpos($methods[$i], '@')) > -1) {

                    $mType = substr($methods[$i], $pos + 1);
                    $methods[$i] = substr($methods[$i], 0, $pos);

                    if(($key = array_search($mType, $types)) > -1) {

                        $type = $types[$key];
                        unset($types[$key]);
                    }
                }

                $type = $type ?: array_shift($types);

                if($i == 0)
                    $arg = array('as' => $route, 'uses' => $class . '@' . $methods[$i] );
                else
                    $arg = $class . '@' . $methods[$i];

                $return = Route::$type( $uri, $arg );

                foreach ($wheres as $search => $where)
                {
                    if(strpos($uri, $search) > -1 || strpos($uri, str_replace('}', '?}', $search)) > -1)
                    {
                        $search = trim($search, '{}');
                        $search = trim($search, '?');

                        $return->where($search, $where);
                    }
                }
            }
        }
    }
}