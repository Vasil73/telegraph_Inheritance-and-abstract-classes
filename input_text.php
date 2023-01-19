<?php

use PHPMailer\PHPMailer\PHPMailer;
 //use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


require_once 'autoload.php';


    function errorHandler(Throwable $exception)
    {
        echo "<div style='background: pink; color: red; font-weight: bold; padding: 10px;'>{$exception->getMessage()}</div>";
        //return false;
    }
    set_exception_handler('errorHandler');


    if (isset($_POST['author']) && isset($_POST['text'])) {
        $author = htmlentities($_POST['author']);
        $text = htmlentities($_POST['text']);

        $newObject = new TelegraphText('author', '', 'test_text_file', 'C:xampp\htdocs\Telegraph_Project\telegraph\file_storage');
        $newObject->editText('text', 'title');

        if (strlen($newObject->text) > 0) {
            $fileStorage = new FileStorage();
            $result = $fileStorage->create($newObject);

            $message = '<div class="alert alert-success" role="alert">Пост успешно опубликован!</div>';

            if ($result && isset($_POST['email'])) {
                $mail = new PHPMailer(true);

                $email = htmlentities($_POST['email']);

                try {
                    //Server settings
                    $mail->SMTPOptions = array(
                        'ssl' => array(
                            'verify_peer' => false,
                            'verify_peer_name' => false,
                            'allow_self_signed' => true
                        )
                    );

                   // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
                    $mail->isSMTP();                                            //Send using SMTP
                    $mail->Host       = 'smtp.yandex.ru';                     //Set the SMTP server to send through
                    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                    $mail->Username   = 'vasil.73@yandex.ru';                     //SMTP username
                    $mail->Password   = '';                               //SMTP password
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
                    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

                    //Recipients
                    $mail->setFrom('vasil.73@yandex.ru', 'Telegraph');
                    $mail->addAddress($_POST['email']);

                    //Content
                    $mail->CharSet = PHPMailer::CHARSET_UTF8;
                    $mail->isHTML(false);
                    $mail->Subject = 'Ваш текст успешно опубликован!';
                    $mail->Body    = $text;

                    // $mail->send(); // Перед включением отправки нужно заполнить логин и пароль от аккаунта на яндексе

                    $message .= '<div class="alert alert-success" role="alert">Уведомление на почту успешно отправлено!</div>';
                } catch (Exception $e) {
                    $message .= '<div class="alert alert-danger" role="alert">Произошла ошибка отправки почтового уведомления!</div>';
                    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                }
            }
        }
    }


 ?>

    <!DOCTYPE html>
    <html lang="ru">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Создание нового поста - Telegraph</title>
        <link href="libs/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
        <script src="libs/bootstrap.min.js"></script>
    </head>
    <body>
    <div class="container">
        <?= $message??'' ?>
            <form class="d-flex justify-content-center flex-column pt-3" action="input_text.php" method="POST">
                <div class="mb-2 col-6 md-3">
                    <label for="author" class="form-label">Ваше имя</label>
                    <input type="text" name="author" id="author" class="form-control" required>
                </div>
                <div class="mb-2 md-3">
                    <label for="text" class="form-label">Текст</label>
                    <textarea name="text" cols="30" rows="10" id="text" class="form-control" required></textarea>
                </div>
                <div class="mb-2 col-4 md-3">
                    <label for="email" class="form-label">Ваш Email</label>
                    <input type="email" name="email" id="email" class="form-control">
                </div>
                <button type="submit" class="btn col-2 mt-3 btn-primary">Отправить</button>
        </form>
        </div>
    </body>
</html>


