<?php

namespace App\Controllers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class HomeController extends BaseController
{
    static protected int $CONTACT_NAME_MAX_LENGTH = 60;
    static protected int $SUBJECT_MAX_LENGTH = 60;
    static protected int $MESSAGE_MAX_LENGTH = 3000;
    static protected int $MESSAGE_MIN_LENGTH = 10;

    public function __construct()
    {
        parent::__construct();
    }

    public function index(): string
    {
        return $this->twig->render('index.html.twig', [
            'q' => array_key_exists('q', $_GET) ? $_GET['q'] : ''
        ]);
    }

    public function post(): void
    {
        $inputs = (object) $_POST;
        [$validated, $errors] = $this->ValidateInputs($inputs);

        if ($validated === false) {
            $_SESSION['flash']['oldInputs'] = $_POST;
            $_SESSION['flash']['errors'] = $errors;

            header('Location: ' . $_ENV['APP_URL'] . '#contato');
            return;
        }

        try {
            $isSpam = $this->AkismetSpamClassifier($inputs);
            $this->LogEmail($inputs, $isSpam);

            if ($isSpam === true) {
                header('Location: ' . $_ENV['APP_URL']);
                return;
            }

            if ($_ENV['APP_DEBUG'] === true) {
                $_SESSION['flash']['success'] = 'Obrigado pela mensagem! Irei responder em breve.';
                header('Location: ' . $_ENV['APP_URL'] . '#contato');
                return;
            }

            $hasSent = $this->SendEmail($inputs);

            if ($hasSent === false) {
                $_SESSION['flash']['errors']['geral'] = 'Ocorreu um erro ao enviar o email, por favor tente mais tarde ou envie diretamente a partir do seu cliente de email favorito: ' . $_ENV['MAIL_ADDRESS'];
                header('Location: ' . $_ENV['APP_URL'] . '#contato');
                exit();
            }

            $_SESSION['flash']['success'] = 'Obrigado pela mensagem! Irei responder em breve.';
            header('Location: ' . $_ENV['APP_URL'] . '#contato');
        } catch (GuzzleException | \Exception $e) {
            if ($_ENV['APP_DEBUG'] === true) {
                echo $e->getMessage();
            } else {
                $_SESSION['flash']['errors']['geral'] = 'Ocorreu um erro ao enviar o email, por favor tente mais tarde ou envie diretamente a partir do seu cliente de email favorito: ' . $_ENV['MAIL_ADDRESS'];

                header('Location: ' . $_ENV['APP_URL'] . '#contato');
                exit();
            }
        }
    }

    protected function ValidateInputs(\stdClass $inputs): array
    {
        $errors = [];

        $email = property_exists($inputs, 'email') && is_string($inputs->email)
            ? trim($inputs->email)
            : '';
        $nome = property_exists($inputs, 'nome') && is_string($inputs->nome)
            ? trim($inputs->nome)
            : '';
        $assunto = property_exists($inputs, 'assunto') && is_string($inputs->assunto)
            ? trim($inputs->assunto)
            : '';
        $mensagem = property_exists($inputs, 'mensagem') && is_string($inputs->mensagem)
            ? trim($inputs->mensagem)
            : '';

        if ($email === '') {
            $errors['email'] = 'O campo Email para Contato é obrigatório.';
        } else if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            $errors['email'] = 'O campo Email para Contato não está num formato válido.';
        } else if (strlen($email) > 254) {
            $errors['email'] = 'O campo Email para Contato é muito longo.';
        } else if (preg_match('/[\r\n]/', $email) === 1) {
            $errors['email'] = 'O campo Email para Contato contém caracteres inválidos.';
        }

        if ($nome === '') {
            $errors['nome'] = 'O campo Nome é obrigatório.';
        } else if (strlen($nome) > static::$CONTACT_NAME_MAX_LENGTH) {
            $errors['nome'] = 'O campo nome precisa ter no máximo ' . static::$CONTACT_NAME_MAX_LENGTH . ' caracteres.';
        } else if (preg_match('/^[\p{L}\p{M}0-9\s\.' . "'" . '-]+$/u', $nome) !== 1) {
            $errors['nome'] = 'O campo Nome contém caracteres inválidos.';
        }

        if ($assunto === '') {
            $errors['assunto'] = 'O campo Assunto é obrigatório.';
        } else if (strlen($assunto) > static::$SUBJECT_MAX_LENGTH) {
            $errors['assunto'] = 'O campo Assunto precisa ter no máximo ' . static::$SUBJECT_MAX_LENGTH . ' caracteres';
        } else if (preg_match('/[\r\n]/', $assunto) === 1) {
            $errors['assunto'] = 'O campo Assunto contém caracteres inválidos.';
        }

        if ($mensagem === '') {
            $errors['mensagem'] = 'O campo Mensagem é obrigatório.';
        } else if (strlen($mensagem) < static::$MESSAGE_MIN_LENGTH) {
            $errors['mensagem'] = 'O campo Mensagem precisa ter no mínimo ' . static::$MESSAGE_MIN_LENGTH . ' caracteres.';
        } else if (strlen($mensagem) > static::$MESSAGE_MAX_LENGTH) {
            $errors['mensagem'] = 'O campo Mensagem precisa ter no máximo ' . static::$MESSAGE_MAX_LENGTH . ' caracteres.';
        } else if (preg_match('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', $mensagem) === 1) {
            $errors['mensagem'] = 'O campo Mensagem contém caracteres inválidos.';
        }

        $inputs->email = $email;
        $inputs->nome = $nome;
        $inputs->assunto = $assunto;
        $inputs->mensagem = $mensagem;

        if (empty($errors) && $this->HasBlockedContent($assunto, $mensagem)) {
            $errors['mensagem'] = 'A mensagem contém conteúdo não permitido.';
        }

        return [
            empty($errors),
            $errors
        ];
    }

    protected function HasBlockedContent(string $assunto, string $mensagem): bool
    {
        $blockedTerms = [
            'casino',
            'bet',
            'viagra',
            'porn',
            'btc',
            'criptomoeda',
            'renda extra',
            'ganhe dinheiro',
            'tráfego pago',
            'backlink',
            'seo garantido',
            'telegram',
            'whatsapp group',
            'emagrecimento',
            'loan',
            'base64_',
            '<?php',
            'shell_',
            'exec(',
            'wget ',
            'curl ',
            'onerror=',
            '<script',
            'http://',
            'https://'
        ];

        $content = mb_strtolower($assunto . ' ' . $mensagem);

        foreach ($blockedTerms as $term) {
            if (str_contains($content, mb_strtolower($term))) {
                return true;
            }
        }

        if (preg_match_all('/https?:\/\//i', $mensagem) >= 2) {
            return true;
        }

        return false;
    }

    protected function SetUpPHPMailer(): PHPMailer
    {
        $mail = new PHPMailer(true);
        $mail->setLanguage('pt_br');

        //Server settings
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = $_ENV['MAIL_HOST'];                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = $_ENV['MAIL_USERNAME'];                     //SMTP username
        $mail->Password   = $_ENV['MAIL_PASSWORD'];                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = $_ENV['MAIL_PORT'];                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
        $mail->CharSet = PHPMailer::CHARSET_UTF8;
        $mail->isHTML(true);                                  //Set email format to HTML

        return $mail;
    }

    /**
     * @throws \Exception
     */
    protected function LogEmail(object $inputs, bool $isSpam): void
    {
        $csvPath = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'emails.csv';

        if (file_exists($csvPath) === false) {
            throw new \Exception('Arquivo de log dos emails não encontrado.');
        }

        $f = fopen($csvPath, 'a');
        if ($f === false) {
            throw new \Exception('Erro ao abrir arquivo de log dos emails.');
        }

        $data = [
            'user_ip' => $_SERVER['REMOTE_ADDR'],
            'user_agent' => $_SERVER['HTTP_USER_AGENT'],
            'referrer' => $_SERVER['HTTP_REFERER'],
            'permalink' => $_ENV['APP_URL'],
            'author_name' => $inputs->nome,
            'author_email' => $inputs->email,
            'content' => $inputs->mensagem,
            'isSpam' => (int) $isSpam,
            'created_at' => date(DATE_ISO8601_EXPANDED),
            'updated_at' => '',
            'deleted_at' => '',
        ];

        $result = fputcsv($f, $data); # Verificar qual exceção é lançada
        if ($result === false) {
            throw new \Exception('Erro ao salvar log de email.');
        }

        fclose($f);
    }

    /**
     * @throws GuzzleException
     */
    protected function AkismetSpamClassifier(object $inputs): bool
    {
        $aksimetRequestBody = [
            'api_key' => $_ENV['AKISMET_API_KEY'],
            'blog' => $_ENV['APP_URL'],
            'user_ip' => $_SERVER['REMOTE_ADDR'],
            'user_agent' => $_SERVER['HTTP_USER_AGENT'],
            'referrer' => $_SERVER['HTTP_REFERER'],
            'permalink' => $_ENV['APP_URL'],
            'comment_type' => 'contact-form',
            'comment_author' => $inputs->nome,
            'comment_author_email' => $inputs->email,
            'comment_content' => $inputs->mensagem,
            'comment_date_gmt' => date(DATE_ISO8601_EXPANDED),
            'blog_lang' => 'pt-BR',
            'blog_charset' => 'UTF-8',
            'is_test' => $_ENV['APP_DEBUG']
        ];

        $client = new Client([
            'base_uri' => 'https://rest.akismet.com/1.1/',
            'verify' => $_ENV['APP_DEBUG'] === false
        ]);

        $response = $client->post('comment-check', [
            'form_params' => $aksimetRequestBody
        ]);
        $body = (string) $response->getBody();

        return $body === 'true';
    }

    /**
     * @throws Exception
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    protected function SendEmail(object $inputs): bool
    {
        $mail = $this->SetUpPHPMailer();

        $mail->Subject = $inputs->assunto;
        $mail->setFrom($_ENV['MAIL_ADDRESS'], $_ENV['MAIL_NAME']);
        $mail->addAddress($_ENV['MAIL_ADDRESS'], $_ENV['MAIL_NAME']);     //Add a recipient
        $mail->addReplyTo($inputs->email, $inputs->nome);

        $mailBody = $this->twig->render('mails/contato-message.html.twig', [
            'nome' => $inputs->nome,
            'message' => $inputs->mensagem
        ]);
        $mail->msgHTML($mailBody);

        return $mail->send();
    }
}
