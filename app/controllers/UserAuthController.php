<?php
namespace App\Controllers;

use App\Core\{Controller, Auth, Session, Helper, CSRF};
use App\Models\UserModel;

class UserAuthController extends Controller
{
    private const REMEMBER_COOKIE = 'tn_remember';
    private const REMEMBER_DAYS   = 30;

    private UserModel $users;
    public function __construct() { $this->users = new UserModel(); }

    public function loginForm(): void
    {
        if (Auth::check()) {
            $this->redirect('/portal/dashboard');
        }

        if (Session::has('login_pending_email')) {
            $this->view('auth.user_login_password', [
                'pageTitle' => 'Staff Login',
                'email'     => Session::get('login_pending_email'),
            ], '');
            return;
        }

        $this->view('auth.user_login', ['pageTitle' => 'Staff Login'], '');
    }

    public function login(): void
    {
        CSRF::validate();

        $stage = $this->post('stage', 'identifier');

        if ($stage === 'password') {
            $this->handlePasswordStage();
            return;
        }

        $this->handleIdentifierStage();
    }

    /** Stage 1 — single box: email or 6-digit PIN */
    private function handleIdentifierStage(): void
    {
        $identifier = trim($this->post('identifier', ''));

        if ($identifier === '') {
            Session::flash('alert_type', 'danger');
            Session::flash('alert_msg',  'Enter your email or PIN.');
            $this->redirect('/login');
        }

        if (preg_match('/^\d{6}$/', $identifier)) {
            $this->attemptPinLogin($identifier);
            return;
        }

        if (str_contains($identifier, '@')) {
            Session::set('login_pending_email', $identifier);
            $this->redirect('/login');
        }

        Session::flash('alert_type', 'danger');
        Session::flash('alert_msg',  'Enter a valid email address or your 6-digit PIN.');
        $this->redirect('/login');
    }

    /** Stage 2 — password, following an email entered in stage 1 */
    private function handlePasswordStage(): void
    {
        $email    = Session::get('login_pending_email', '');
        $password = $this->post('password', '');

        if (!$email || !$password) {
            Session::flash('alert_type', 'danger');
            Session::flash('alert_msg',  'Enter your password.');
            $this->redirect('/login');
        }

        $user = $this->users->findByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            Session::flash('alert_type', 'danger');
            Session::flash('alert_msg',  'Invalid email or password.');
            $this->redirect('/login');
        }

        if ($user['role_slug'] === 'admin') {
            Session::delete('login_pending_email');
            Session::flash('alert_type', 'warning');
            Session::flash('alert_msg',  'Admin accounts must use the admin login page.');
            $this->redirect('/login');
        }

        Session::delete('login_pending_email');
        Auth::login($user);
        $this->users->updateLastLogin($user['id']);

        // No PIN yet (first login, or after a 30-day expiry) — set one up
        if (empty($user['pin'])) {
            $this->redirect('/portal/set-pin');
        }

        $this->issueRememberCookie($user['id']);
        $this->redirectByRole($user['role_slug'] ?? '');
    }

    /** PIN quick-login — only works if this device has a valid remember cookie */
    private function attemptPinLogin(string $pin): void
    {
        [$userId, $token] = $this->readRememberCookie();

        if (!$userId || !$token) {
            Session::flash('alert_type', 'warning');
            Session::flash('alert_msg',  'PIN login isn\'t available on this device. Please sign in with your email.');
            $this->redirect('/login');
        }

        $user = $this->users->findByIdWithRole($userId);

        if (!$user
            || empty($user['remember_token'])
            || empty($user['remember_expires'])
            || strtotime($user['remember_expires']) < time()
            || !hash_equals($user['remember_token'], hash('sha256', $token))
        ) {
            $this->clearRememberCookie($userId);
            Session::flash('alert_type', 'warning');
            Session::flash('alert_msg',  'Your device login has expired. Please sign in with your email and password.');
            $this->redirect('/login');
        }

        if (!$this->users->verifyPin($user, $pin)) {
            Session::flash('alert_type', 'danger');
            Session::flash('alert_msg',  'Incorrect PIN.');
            $this->redirect('/login');
        }

        Auth::login($user);
        $this->users->updateLastLogin($user['id']);
        $this->issueRememberCookie($user['id']); // slide the 30-day window
        $this->redirectByRole($user['role_slug'] ?? '');
    }

    /** GET — first login (or PIN reset) prompt */
    public function setPinForm(): void
    {
        $this->requireAuth();
        $this->view('auth.set_pin', ['pageTitle' => 'Set your PIN'], '');
    }

    /** POST — save the 6-digit PIN and start remembering this device */
    public function setPin(): void
    {
        $this->requireAuth();
        CSRF::validate();

        $fromProfile = $this->post('redirect_to') === 'profile';
        $failUrl     = $fromProfile ? '/portal/profile' : '/portal/set-pin';

        $pin     = trim($this->post('pin', ''));
        $confirm = trim($this->post('pin_confirm', ''));

        if (!preg_match('/^\d{6}$/', $pin)) {
            $this->flash('danger', 'PIN must be exactly 6 digits.');
            $this->redirect($failUrl);
        }
        if ($pin !== $confirm) {
            $this->flash('danger', 'PINs do not match.');
            $this->redirect($failUrl);
        }

        $userId = Auth::id();
        $this->users->setPin($userId, $pin);
        $this->issueRememberCookie($userId);

        $this->flash('success', 'PIN saved.');

        if ($fromProfile) {
            $this->redirect('/portal/profile');
            return;
        }
        $this->redirectByRole(Auth::role() ?? '');
    }

    /** Cancel stage 2 (password prompt) and go back to the email/PIN box */
    public function backToIdentifier(): void
    {
        Session::delete('login_pending_email');
        $this->redirect('/login');
    }

    public function logout(): void
    {
        // Ends the session only — the device stays remembered (PIN still works)
        // until the 30-day window elapses. See forgetDevice() to explicitly forget it.
        Auth::logout();
        $this->redirect('/login');
    }

    /** Explicitly forget this device (clears PIN quick-login) — e.g. "not your device" */
    public function forgetDevice(): void
    {
        [$userId] = $this->readRememberCookie();
        if ($userId) $this->clearRememberCookie($userId);
        Auth::logout();
        $this->redirect('/login');
    }

    /* ── remember-device cookie helpers ── */

    private function issueRememberCookie(int $userId): void
    {
        $token     = bin2hex(random_bytes(32));
        $expiresAt = date('Y-m-d H:i:s', strtotime('+' . self::REMEMBER_DAYS . ' days'));
        $this->users->setRememberToken($userId, $token, $expiresAt);

        $this->setRememberCookieRaw($userId . '.' . $token, time() + self::REMEMBER_DAYS * 86400);
    }

    private function clearRememberCookie(int $userId): void
    {
        $this->users->clearRememberToken($userId);
        $this->setRememberCookieRaw('', time() - 3600);
    }

    /**
     * The array-options setcookie() form (needed for SameSite) only works on
     * PHP 7.3+. Live hosting may run older PHP, where passing an array as
     * the 3rd/4th positional arg silently drops the expiry — turning this
     * into a session-only cookie that vanishes as soon as the browser closes.
     * Fall back to the classic signature (no SameSite) below PHP 7.3.
     */
    private function setRememberCookieRaw(string $value, int $expire): void
    {
        $secure = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';

        if (PHP_VERSION_ID >= 70300) {
            setcookie(self::REMEMBER_COOKIE, $value, [
                'expires'  => $expire,
                'path'     => '/',
                'secure'   => $secure,
                'httponly' => true,
                'samesite' => 'Lax',
            ]);
        } else {
            setcookie(self::REMEMBER_COOKIE, $value, $expire, '/', '', $secure, true);
        }
    }

    /** @return array{0:?int,1:?string} */
    private function readRememberCookie(): array
    {
        $raw = $_COOKIE[self::REMEMBER_COOKIE] ?? '';
        if (!str_contains($raw, '.')) return [null, null];
        [$id, $token] = explode('.', $raw, 2);
        if (!ctype_digit($id) || $token === '') return [null, null];
        return [(int)$id, $token];
    }

    private function redirectByRole(string $role): void
    {
        if ($role === 'ad_owner') {
            $this->redirect('/portal/my-ads');
        }
        $this->redirect('/portal/dashboard');
    }
}
