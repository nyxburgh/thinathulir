<?php
namespace App\Core;

abstract class Controller
{
    protected function view(string $view, array $data = [], string $layout = 'admin'): void
    {
        $old    = Session::getFlash('form_old', []);
        $errors = Session::getFlash('form_errors', []);
        extract($data);
        $old     = $old ?? [];
        $errors  = $errors ?? [];
        $csrf    = CSRF::token();
        $auth    = Auth::user();
        $r       = defined('ASSET_URL') ? rtrim(ASSET_URL, '/') . '/public' : '/public';
        $baseUrl = defined('BASE_URL')  ? BASE_URL  : '';

        ob_start();
        $viewFile = VIEW_PATH . '/' . str_replace('.', '/', $view) . '.php';
        if (!file_exists($viewFile)) {
            throw new \RuntimeException("View not found: {$viewFile}");
        }
        require $viewFile;
        $content = ob_get_clean();

        if ($layout) {
            $layoutFile = VIEW_PATH . '/layouts/' . $layout . '.php';
            if (file_exists($layoutFile)) {
                require $layoutFile;
                return;
            }
        }
        echo $content;
    }

    /**
     * Determines which layout to use based on role:
     * - admin           → 'admin'      (dark panel)
     * - chief_editor    → 'editor_portal' (white, full editorial nav)
     * - others          → 'portal'     (white, simple nav)
     */
    protected function layout(): string
    {
        return match(Auth::role()) {
            'admin'                        => 'admin',
            'chief_editor', 'staff_reporter' => 'editor_portal',
            default                        => 'portal',
        };
    }

    protected function json(mixed $data, int $code = 200): void  { Helper::json($data, $code); }
    protected function redirect(string $url): void                { Helper::redirect($url); }
    protected function back(): void                               { $this->redirect($_SERVER['HTTP_REFERER'] ?? '/portal/dashboard'); }

    protected function requireAuth(): void
    {
        if (!Auth::check() && !Session::has('contributor_id')) {
            Helper::redirect('/login');
        }
    }

    protected function requireAdmin(): void
    {
        if (!Auth::isAdmin()) {
            http_response_code(403);
            die('<h1>403 — Admin access required</h1>');
        }
    }

    protected function requireRole(string ...$roles): void
    {
        if (!Auth::check()) {
            $uri = $_SERVER['REQUEST_URI'] ?? '';
            $isAdminUrl = (strpos($uri, '/admin/') !== false || strpos($uri, '/panel/') !== false);
            Helper::redirect($isAdminUrl ? '/admin/login' : '/login');
        }
        if (!in_array(Auth::role(), $roles)) {
            http_response_code(403);
            require VIEW_PATH . '/errors/403.php';
            exit;
        }
    }

    protected function requireCan(string $permission): void
    {
        if (!Auth::check()) {
            $uri = $_SERVER['REQUEST_URI'] ?? '';
            $isAdminUrl = (strpos($uri, '/admin/') !== false || strpos($uri, '/panel/') !== false);
            Helper::redirect($isAdminUrl ? '/admin/login' : '/login');
        }
        if (!Auth::can($permission)) {
            http_response_code(403);
            require VIEW_PATH . '/errors/403.php';
            exit;
        }
    }

    protected function validateCSRF(): void                       { CSRF::validate(); }
    protected function input(string $key, mixed $default = null): mixed { return $_POST[$key] ?? $_GET[$key] ?? $default; }
    protected function post(string $key, mixed $default = null): mixed  { return $_POST[$key] ?? $default; }
    protected function get(string $key, mixed $default = null): mixed   { return $_GET[$key]  ?? $default; }

    protected function flash(string $type, string $message): void
    {
        Session::flash('alert_type', $type);
        Session::flash('alert_msg',  $message);
    }

    /**
     * Validation failed — flash field errors + submitted values, then redirect
     * back to the form so the user doesn't have to retype everything.
     */
    protected function backWithErrors(string $url, array $errors, ?array $old = null): void
    {
        Session::flash('form_errors', $errors);
        Session::flash('form_old', $old ?? $_POST);
        $this->flash('danger', 'கீழே உள்ள பிழைகளை சரிசெய்யவும்.');
        $this->redirect($url);
    }

    public function middleware(): void {}
}
