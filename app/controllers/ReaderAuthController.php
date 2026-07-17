<?php
namespace App\Controllers;

use App\Core\{Controller, GoogleOAuth, Session, Helper, CSRF};
use App\Models\{ReaderModel, RatingModel};

class ReaderAuthController extends Controller
{
    private string $redirectUri;

    public function __construct()
    {
        $cfg = require CONFIG_PATH . '/app.php';
        $base = rtrim($cfg['url'], '/');
        $this->redirectUri = $base . '/public/auth/reader/callback';
    }

    public function googleRedirect(): void
    {
        $state = bin2hex(random_bytes(16));
        Session::set('reader_oauth_state', $state);
        Session::set('reader_return', $_GET['return'] ?? '/');
        Helper::redirect(GoogleOAuth::authUrl($this->redirectUri, $state));
    }

    public function callback(): void
    {
        $code = $_GET['code'] ?? '';
        if (!$code) { Helper::redirect('/'); }

        $tokens  = GoogleOAuth::exchangeCode($code, $this->redirectUri);
        $profile = $tokens ? GoogleOAuth::getProfile($tokens['access_token']) : null;

        if (!$profile) {
            Session::flash('alert_type', 'danger');
            Session::flash('alert_msg', 'Google login failed.');
            Helper::redirect('/');
        }

        $model    = new ReaderModel();
        $readerId = $model->upsertFromGoogle($profile);
        $reader   = $model->find($readerId);

        session_regenerate_id(true);
        Session::set('reader_id', $readerId);
        Session::set('reader',    $reader);

        $return = Session::get('reader_return', '/');
        Session::delete('reader_return');

        // First-time login: redirect to T&C + district agreement
        $target    = empty($reader['has_agreed_terms']) ? '/reader/agree' : $return;
        $targetUrl = BASE_URL . '/public' . $target;

        // Detect webview UA — direct redirect, no popup JS
        $ua        = $_SERVER['HTTP_USER_AGENT'] ?? '';
        $isWebview = str_contains($ua, 'wv')
                  || str_contains($ua, 'WebView')
                  || (str_contains($ua, 'Android') && !str_contains($ua, 'Chrome/'));

        if ($isWebview) {
            Helper::redirect($target);
            exit;
        }

        // Browser — close popup if opened in one, else full redirect
        echo '<!DOCTYPE html><html><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"></head><body>
<script>
if(window.opener&&!window.opener.closed){window.opener.location.reload();window.close();}
else{window.location=' . json_encode($targetUrl) . ';}
</script>
<p>Redirecting... <a href="' . htmlspecialchars($targetUrl) . '">Click here</a></p>
</body></html>';
        exit;
    }

    public function logout(): void
    {
        Session::delete('reader_id');
        Session::delete('reader');
        Session::delete('reader_oauth_state');
        if (ini_get('session.use_cookies')) {
            $p = session_get_cookie_params();
            setcookie(session_name(), '', time()-42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
        }
        session_destroy();
        Helper::redirect('/');
    }

    public function rate(): void
    {
        header('Content-Type: application/json');
        if (!Session::get('reader_id')) {
            echo json_encode(['error' => 'Login required', 'redirect' => '/auth/reader/login']); exit;
        }
        // Manual CSRF check — return JSON on failure (AJAX endpoint)
        $token = $_POST['_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
        if (!CSRF::verify($token)) {
            echo json_encode(['error' => 'Session expired. Please refresh the page.']); exit;
        }

        $articleId = (int)($_POST['article_id'] ?? 0);
        $rating    = max(1, min(5, (int)($_POST['rating'] ?? 0)));
        $review    = trim(htmlspecialchars($_POST['review'] ?? '', ENT_QUOTES, 'UTF-8'));

        if (!$articleId || !$rating) {
            Helper::json(['error' => 'Invalid input'], 422);
        }

        try {
            $ratingModel = new RatingModel();
            $ratingModel->upsert($articleId, Session::get('reader_id'), $rating, $review);
            $stats = $ratingModel->forArticle($articleId);
            Helper::json(['success' => true, 'stats' => $stats]);
        } catch (\Exception $e) {
            error_log('Rate error: ' . $e->getMessage());
            Helper::json(['error' => 'Could not save rating. Please try again.'], 500);
        }
    }
}
