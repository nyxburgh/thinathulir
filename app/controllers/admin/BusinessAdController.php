<?php
namespace App\Controllers\Admin;

use App\Core\{Controller, Auth, CSRF, Helper, Session};
use App\Models\{BusinessAdModel, AdPackageModel, AdModel, CategoryModel, LocationModel};

class BusinessAdController extends Controller
{
    private BusinessAdModel $ads;
    private AdPackageModel  $pkgs;

    public function middleware(): void { $this->requireCan('manage_ads'); }
    protected function layout(): string { return Auth::role()==='admin' ? 'admin' : 'editor_portal'; }
    private function base(): string    { return Auth::role()==='admin' ? '/admin/business-ads' : '/portal/ads'; }

    public function __construct()
    {
        $this->ads  = new BusinessAdModel();
        $this->pkgs = new AdPackageModel();
    }

    // ── List ─────────────────────────────────────────────────
    public function index(): void
    {
        $page   = max(1,(int)$this->get('page',1));
        $search = trim($this->get('search',''));
        $status = trim($this->get('status',''));
        $ads    = $this->ads->allWithPackage($page, 20, $search, $status);
        $this->view('admin.business_ads.index',[
            'pageTitle' => 'Business Ads',
            'ads'       => $ads['data'],
            'total'     => $ads['total'],
            'page'      => $ads['page'],
            'per_page'  => $ads['per_page'],
            'search'    => $search,
            'statusFilter' => $status,
            'adsBase'   => $this->base(),
        ],$this->layout());
    }

    // ── Create ───────────────────────────────────────────────
    public function create(): void
    {
        $this->view('admin.business_ads.create',[
            'pageTitle' => 'New Advertisement',
            'packages'  => $this->pkgs->active(),
            'districts' => (new LocationModel())->allDistricts(),
            'categories'=> (new CategoryModel())->allWithParent(),
            'adsBase'   => $this->base(),
        ],$this->layout());
    }

    private function normalizePhone(string $phone): string
    {
        $digits = preg_replace('/\D/', '', $phone);
        // Only strip a leading "91" country code when it's actually a 12-digit
        // 91+10digit number — a plain 10-digit number that happens to start
        // with "91" (e.g. 9123456789) must NOT be truncated to 8 digits.
        if (strlen($digits) === 12 && str_starts_with($digits, '91')) {
            $digits = substr($digits, 2);
        }
        return $digits;
    }

    private function validateContact(string $phone, string $email, int $excludeId = 0): array
    {
        $errors = [];
        if ($phone !== '' && !preg_match('/^[6-9]\d{9}$/', $this->normalizePhone($phone))) {
            $errors['contact_phone'] = 'சரியான 10-இலக்க மொபைல் எண்ணை உள்ளிடவும்.';
        } elseif ($phone !== '' && $this->ads->phoneExists($phone, $excludeId)) {
            $errors['contact_phone'] = 'இந்த மொபைல் எண் ஏற்கனவே பதிவு செய்யப்பட்டுள்ளது.';
        }
        if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['contact_email'] = 'சரியான மின்னஞ்சல் முகவரியை உள்ளிடவும்.';
        } elseif ($email !== '' && $this->ads->emailExists($email, $excludeId)) {
            $errors['contact_email'] = 'இந்த மின்னஞ்சல் ஏற்கனவே பதிவு செய்யப்பட்டுள்ளது.';
        }
        return $errors;
    }

    // GET — AJAX field-level duplicate check for contact_phone / contact_email
    public function checkField(): void
    {
        $field     = $this->get('field', '');
        $value     = trim($this->get('value', ''));
        $excludeId = (int)$this->get('exclude_id', 0);
        if (!in_array($field, ['contact_phone', 'contact_email'], true) || $value === '') {
            $this->json(['exists' => false]);
        }
        $exists = $field === 'contact_phone'
            ? $this->ads->phoneExists($value, $excludeId)
            : $this->ads->emailExists($value, $excludeId);
        $this->json(['exists' => $exists]);
    }

    public function store(): void
    {
        CSRF::validate();
        $name  = Helper::sanitize($this->post('business_name',''));
        $person = Helper::sanitize($this->post('contact_person',''));
        $phone  = trim($this->post('contact_phone',''));
        $email  = trim($this->post('contact_email',''));

        $errors = [];
        if (!$name)   $errors['business_name']   = 'நிறுவனத்தின் பெயர் அவசியம்.';
        if (!$person) $errors['contact_person']  = 'தொடர்பு நபர் பெயர் அவசியம்.';
        if (!$phone)  $errors['contact_phone']   = 'மொபைல் எண் அவசியம்.';
        if (!(int)$this->post('package_id',0)) $errors['package_id'] = 'தொகுப்பை தேர்ந்தெடுக்கவும்.';
        $errors = array_merge($errors, $this->validateContact($phone, $email));

        if ($errors) { $this->backWithErrors($this->base().'/create', $errors); }

        $pkgId = (int)$this->post('package_id',0);
        $pkg   = $pkgId ? $this->pkgs->find($pkgId) : null;
        $from  = $this->post('valid_from', date('Y-m-d'));
        $customDays = (int)$this->post('custom_days',0);
        $until = $pkg ? $this->pkgs->validUntil($pkg, $from, $customDays) : date('Y-m-d', strtotime('+6 months'));

        // Resolve slot_id from package
        $slotId = 0;
        if ($pkg) {
            $db = \App\Core\Database::getInstance();
            try {
                $s = $db->prepare("SELECT id FROM tn_ad_slots WHERE type=? AND is_active=1 ORDER BY id LIMIT 1");
                $s->execute([$pkg['slot_type'] !== 'any' ? $pkg['slot_type'] : 'square']);
                $slotId = (int)$s->fetchColumn();
            } catch (\Exception $e) {}
        }
        if (!$slotId) $slotId = 1;

        $id = $this->ads->insert([
            'business_name'   => $name,
            'contact_person'  => Helper::sanitize($this->post('contact_person','')),
            'contact_phone'   => Helper::sanitize($this->post('contact_phone','')),
            'contact_email'   => Helper::sanitize($this->post('contact_email','')),
            'website_url'     => Helper::sanitize($this->post('website_url','')),
            'facebook_url'    => Helper::sanitize($this->post('facebook_url','')),
            'instagram_url'   => Helper::sanitize($this->post('instagram_url','')),
            'youtube_url'     => Helper::sanitize($this->post('youtube_url','')),
            'address'         => Helper::sanitize($this->post('address','')),
            'small_desc'      => Helper::sanitize($this->post('small_desc','')),
            'district_id'     => (int)$this->post('district_id',0) ?: null,
            'display_type'    => $this->post('display_type','global'),
            'category_id'     => (int)$this->post('category_id',0) ?: null,
            'package_id'      => $pkgId ?: null,
            'slot_id'         => $slotId,
            'valid_from'      => $from,
            'valid_until'     => $until,
            'payment_amount'  => strlen($this->post('payment_amount','')) ? (float)$this->post('payment_amount',0) : null,
            'payment_ref'     => Helper::sanitize($this->post('payment_ref','')),
            'payment_note'    => Helper::sanitize($this->post('payment_note','')),
            'payment_status'  => 'pending',
            'status'          => 'pending',
            'submitted_by'    => Auth::id(),
            'notes'           => Helper::sanitize($this->post('notes','')),
        ]);

        $this->flash('success','Advertisement created. Now upload images.');
        $this->redirect($this->base().'/show/'.$id);
    }

    // ── View ─────────────────────────────────────────────────
    public function show(string $id): void
    {
        $ad = $this->ads->findWithDetails((int)$id);
        if (!$ad) { $this->flash('danger','Not found.'); $this->redirect($this->base()); }
        $pkg   = $ad['package_id'] ? $this->pkgs->find((int)$ad['package_id']) : null;
        $news  = $this->ads->sponsoredArticles((int)$id);
        $quota = $pkg ? (int)($pkg['news_quota'] ?? 0) : 0;
        $used  = count($news);
        $this->view('admin.business_ads.show',[
            'pageTitle' => $ad['business_name'],
            'ad'        => $ad,
            'pkg'       => $pkg,
            'news'      => $news,
            'newsQuota' => $quota,
            'newsUsed'  => $used,
            'adsBase'   => $this->base(),
        ],$this->layout());
    }

    // ── Edit ─────────────────────────────────────────────────
    public function edit(string $id): void
    {
        $ad = $this->ads->findWithDetails((int)$id);
        if (!$ad) { $this->flash('danger','Not found.'); $this->redirect($this->base()); }
        $this->view('admin.business_ads.edit',[
            'pageTitle'  => 'Edit — '.$ad['business_name'],
            'ad'         => $ad,
            'packages'   => $this->pkgs->active(),
            'districts'  => (new LocationModel())->allDistricts(),
            'categories' => (new CategoryModel())->allWithParent(),
            'adsBase'    => $this->base(),
        ],$this->layout());
    }

    public function update(string $id): void
    {
        CSRF::validate();
        $ad = $this->ads->find((int)$id);
        if (!$ad) { $this->flash('danger','Not found.'); $this->redirect($this->base()); }

        $name  = Helper::sanitize($this->post('business_name',''));
        $phone = trim($this->post('contact_phone',''));
        $email = trim($this->post('contact_email',''));

        $errors = [];
        if (!$name) $errors['business_name'] = 'நிறுவனத்தின் பெயர் அவசியம்.';
        $errors = array_merge($errors, $this->validateContact($phone, $email, (int)$id));

        if ($errors) { $this->backWithErrors($this->base().'/edit/'.$id, $errors); }

        $pkgId = (int)$this->post('package_id',0) ?: null;
        $pkg   = $pkgId ? $this->pkgs->find($pkgId) : null;
        $from  = $this->post('valid_from', $ad['valid_from']);
        $customDays = (int)$this->post('custom_days',0);
        $until = $pkg ? $this->pkgs->validUntil($pkg, $from, $customDays) : $this->post('valid_until', $ad['valid_until']);

        $data = [
            'business_name'  => Helper::sanitize($this->post('business_name','')),
            'contact_person' => Helper::sanitize($this->post('contact_person','')),
            'contact_phone'  => Helper::sanitize($this->post('contact_phone','')),
            'contact_email'  => Helper::sanitize($this->post('contact_email','')),
            'website_url'    => Helper::sanitize($this->post('website_url','')),
            'facebook_url'   => Helper::sanitize($this->post('facebook_url','')),
            'instagram_url'  => Helper::sanitize($this->post('instagram_url','')),
            'youtube_url'    => Helper::sanitize($this->post('youtube_url','')),
            'address'        => Helper::sanitize($this->post('address','')),
            'small_desc'     => Helper::sanitize($this->post('small_desc','')),
            'district_id'    => (int)$this->post('district_id',0) ?: null,
            'display_type'   => $this->post('display_type','global'),
            'category_id'    => (int)$this->post('category_id',0) ?: null,
            'package_id'     => $pkgId,
            'valid_from'     => $from,
            'valid_until'    => $until,
            'status'         => $this->post('status', $ad['status']),
            'notes'          => Helper::sanitize($this->post('notes','')),
        ];
        if (strlen($this->post('payment_amount','')) > 0)
            $data['payment_amount'] = (float)$this->post('payment_amount',0);
        if ($this->post('payment_ref'))
            $data['payment_ref'] = Helper::sanitize($this->post('payment_ref',''));
        if ($this->post('payment_note'))
            $data['payment_note'] = Helper::sanitize($this->post('payment_note',''));
        if ($this->post('payment_status'))
            $data['payment_status'] = $this->post('payment_status');

        $this->ads->update((int)$id, $data);
        $this->flash('success','Updated.');
        $this->redirect($this->base().'/show/'.$id);
    }

    // ── Images page ──────────────────────────────────────────
    public function images(string $id): void
    {
        $ad = $this->ads->findWithDetails((int)$id);
        if (!$ad) { $this->flash('danger','Not found.'); $this->redirect($this->base()); }
        $pkg = $ad['package_id'] ? $this->pkgs->find((int)$ad['package_id']) : null;
        $this->view('admin.business_ads.images',[
            'pageTitle' => 'Images — '.$ad['business_name'],
            'ad'        => $ad,
            'pkg'       => $pkg,
            'adsBase'   => $this->base(),
        ],$this->layout());
    }

    public function assignImage(string $imageId): void
    {
        CSRF::validate();
        $type = $this->post('display_type','square');
        if (!in_array($type,['square','horizontal','vertical'])) $type='square';
        try {
            \App\Core\Database::getInstance()
                ->prepare("UPDATE tn_ad_images SET display_type=? WHERE id=?")
                ->execute([$type, (int)$imageId]);
        } catch (\Exception $e) {}
        $ref = $_SERVER['HTTP_REFERER'] ?? $this->base();
        $this->redirect($ref);
    }

    public function uploadImage(string $id): void
    {
        CSRF::validate();
        $ad = $this->ads->find((int)$id);
        if (!$ad) { $this->json(['error'=>'Not found'],404); }
        $slotType = $this->post('slot_type','square');
        $file = $_FILES['image'] ?? null;
        if (!$file || $file['error'] !== UPLOAD_ERR_OK)
            { $this->json(['error'=>'No file'],400); }
        $ok = $this->ads->uploadImage((int)$id, $file, '', '', $slotType);
        if (!$ok) { $this->json(['error'=>'Upload failed'],500); }
        $this->json(['success'=>true]);
    }

    public function deleteImage(string $imageId): void
    {
        CSRF::validate();
        $this->ads->deleteImage((int)$imageId);
        $this->flash('success','Image removed.');
        $ref = $_SERVER['HTTP_REFERER'] ?? $this->base();
        $this->redirect($ref);
    }

    // ── Payment confirm ──────────────────────────────────────
    public function confirmPayment(string $id): void
    {
        CSRF::validate();
        $ref  = Helper::sanitize($this->post('payment_ref',''));
        $note = Helper::sanitize($this->post('payment_note',''));
        $this->ads->update((int)$id,[
            'payment_status'       => 'confirmed',
            'payment_ref'          => $ref,
            'payment_note'         => $note,
            'payment_confirmed_by' => Auth::id(),
            'payment_confirmed_at' => Helper::now(),
            'status'               => 'active',
        ]);
        $this->flash('success','Payment confirmed. Ad is now Active.');
        $this->redirect($this->base().'/show/'.$id);
    }

    // ── Toggle status ────────────────────────────────────────
    public function toggleStatus(string $id): void
    {
        CSRF::validate();
        $ad = $this->ads->find((int)$id);
        if (!$ad) { $this->redirect($this->base()); }
        $new = $ad['status'] === 'active' ? 'paused' : 'active';
        $this->ads->update((int)$id,['status'=>$new]);
        $this->flash('success','Status updated.');
        $this->redirect($this->base().'/show/'.$id);
    }

    public function delete(string $id): void
    {
        CSRF::validate();
        $this->ads->delete((int)$id);
        $this->flash('success','Deleted.');
        $this->redirect($this->base());
    }
}
