<?php

require_once 'db.php';


// $users = Database::getInstance()->get('users', ['user_name', '=', 'Test']);

// if($users->error()){
//     echo 'We have an error';
// }else {
//     foreach($users->results() as $user){
//         echo $user->user_name.BR;
//     }
// } 
 echo 1;

Database::getInstance()->update('users', 6, [
    'user_name' => '23232',
    'user_password' => '23423432z'
]);

