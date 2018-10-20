<?php

return [
    ['GET',  "/", ["controller" => "DefaultController", "action" => "Index"]],

    ['GET',  "/login", ["controller" => "UserController", "action" => "Login"]],
    ['GET',  "/logout", ["controller" => "UserController", "action" => "Logout"]],

    ['GET',  "/code", ["controller" => "CodeController", "action" => "Blank"], ["template"=>"[\w]+"]], /* /code or /code?template=default */
    ['POST', "/code/save", ["controller" => "CodeController", "action" => "Save"]],
    ['GET',  "/:user/code/:hash", ["controller" => "CodeController", "action" => "Editor"], ["user"=>'[\w]+', "hash"=>'[\w]{10}']], /* /olton/code/wEAK85evGp */

    ['GET',  "/:user/full/:code", ["controller" => "CodeController", "action" => "Full"], ["user"=>'[\w]+', "code"=>'[\w]{10}']], /* /olton/full/wEAK85evGp */
    ['GET',  "/:user/debug/:code", ["controller" => "CodeController", "action" => "Debug"], ["user"=>'[\w]+', "code"=>'[\w]{10}']], /* /olton/debug/wEAK85evGp */
];
