<?php
namespace App\Models;

use App\Core\Model;

class AdPackageModel extends Model
{
    protected string $table = 'tn_ad_packages';

    public function all(string $orderBy = 'sort_order', string $dir = 'ASC'): array
    {
        return $this->fetchAll("SELECT * FROM tn_ad_packages ORDER BY sort_order ASC");
    }

    public function active(): array
    {
        return $this->fetchAll("SELECT * FROM tn_ad_packages WHERE is_active=1 ORDER BY sort_order ASC");
    }

    public function allPackages(): array { return $this->all(); }

    public function find(int $id): array|false
    {
        return $this->fetchOne("SELECT * FROM tn_ad_packages WHERE id=?", [$id]);
    }

    public function findByCode(string $code): array|false
    {
        return $this->fetchOne("SELECT * FROM tn_ad_packages WHERE code=?", [$code]);
    }

    // Compute validity end date from package + start date
    public function validUntil(array $pkg, string $from, int $customDays = 0): string
    {
        $start = new \DateTime($from);
        if ($pkg['slot_type'] === 'vertical' || !empty($pkg['vt_duration_days']) && !$pkg['includes_square'] && !$pkg['includes_horizontal']) {
            $days = $customDays ?: ($pkg['vt_duration_days'] ?? 30);
            $start->modify("+{$days} days");
        } else {
            $months = max(
                (int)($pkg['sq_duration_months'] ?? 0),
                (int)($pkg['hr_duration_months'] ?? 0),
                (int)($pkg['vt_duration_days'] ? 1 : 0)
            );
            if ($months) $start->modify("+{$months} months");
            else $start->modify("+6 months");
        }
        return $start->format('Y-m-d');
    }

    public function yearlyPrice(array $pkg): float
    {
        $disc = 1 - (($pkg['yearly_discount_pct'] ?? 10) / 100);
        return round($pkg['price_inr'] * 2 * $disc, 2);
    }

    // ── Subscriptions (keep for backward compat) ─────────────
    public function subscribe(array $data): int
    {
        $cols   = implode(', ', array_map(fn($k) => "`{$k}`", array_keys($data)));
        $places = implode(', ', array_fill(0, count($data), '?'));
        return (int)$this->fetchColumn(
            "INSERT INTO tn_ad_package_subscriptions ({$cols}) VALUES ({$places}); SELECT LAST_INSERT_ID()",
            array_values($data)
        );
    }
}
