<?php

session_start();

require_once 'db.php';
require_once 'config.php';
require_once 'validation.php';
require_once 'input.php';
require_once 'session.php';
require_once 'token.php';
require_once 'user.php';
require_once 'redirect.php';



// $users = Database::getInstance()->get('users', ['user_name', '=', 'Test']);

// if($users->error()){
//     echo 'We have an error';
// }else {
//     foreach($users->results() as $user){
//         echo $user->user_name.BR;
//     }
// } 

// Database::getInstance()->update('users', 1, [
//     'user_name' => 'Alex',
//     'user_password' => 'testpassword'
// ]);

// echo Config::get('url');

if (Input::exists()) {
    if (Token::check(Input::get('token'))) {
        $validate = new Validate();
        $validation = $validate->check(
            $_POST,
            [
                'user_name' => [
                    'required'  => true,
                    'min'       => 2,
                    'max'       => 15,
                    'unique'    => 'users'
                ],
                'password' => [
                    'required'  => true,
                    'min'       => 3
                ],
                'password_again' => [
                    'required' => true,
                    'matches'  => 'password'
                ]
            ]
        );

        if ($validation->passed()) {
            $user = new User();
            $user -> create([
                'user_name' => Input::get('username'),
                'user_password' => password_hash(Input::get('passowrd'), PASSWORD_DEFAULT)
            ]);
            Session::flash('succes', 'registration succes');
            header('Location: /test.php');
        } else {
            foreach ($validation->errors() as $error) {
                echo $error . '</br>';
            }
        }
    }
};
?>

<form action="" method="post">
    <?php echo Session::flash('success'); ?>
    <div class="field">
        <label for="username">Username</label>
        <input type="text" name="username" value="<?php echo Input::get('username') ?>">
    </div>

    <div class="field">
        <label for="">Password</label>
        <input type="passowrd" name="password">
    </div>

    <div class="field">
        <label for="">Passowrd Again</label>
        <input type="password" name="password_again">
    </div>
    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
    <div class="field">
        <button type="submit">Submit</button>
    </div>
</form>