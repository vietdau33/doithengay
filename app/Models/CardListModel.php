<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CardListModel extends Model
{
    use HasFactory;

    protected $table = 'card_list_status';

    public function getStatusHtml(): string
    {
        return match ($this->active) {
            0 => '<span class="text-danger">Không kích hoạt</span>',
            1 => '<span class="text-success">Đang kích hoạt</span>',
            default => '',
        };
    }

    public function getAutoHtml(): string
    {
        return match ($this->auto) {
            0 => '<span class="text-danger">Không auto</span>',
            1 => '<span class="text-success">Đang auto</span>',
            default => '',
        };
    }

    public function getBtnStatusHtml($typeCard): string
    {
        $btn = $this->isActive() ? 'btn-danger' : 'btn-primary';
        $text = $this->isActive() ? 'Hủy kích hoạt' : 'Kích hoạt';
        $href = route("admin.feature.$typeCard.post", [
            'name' => $this->name,
            'type' => 'active'
        ]);
        return "<a class=\"btn $btn\" href=\"$href\">$text</a>";
    }

    public function getBtnAutoHtml($typeCard): string
    {
        $btn = $this->isAuto() ? 'btn-danger' : 'btn-primary';
        $text = $this->isAuto() ? 'Hủy auto' : 'Bật auto';
        $href = route("admin.feature.$typeCard.post", [
            'name' => $this->name,
            'type' => 'auto'
        ]);
        return "<a class=\"btn $btn\" href=\"$href\">$text</a>";
    }

    public function isActive(): bool
    {
        return $this->active === 1;
    }

    public function isAuto(): bool
    {
        return $this->auto === 1;
    }

    public static function getBillActive(): array
    {
        $bills = self::select(['name'])->whereType('bill')->whereActive(1)->get()->toArray();
        return array_reduce($bills, function ($result, $bill) {
            $name = explode('|', $bill['name']);
            $result[$name[0]][] = $name[1];
            return $result;
        }, []);
    }

    public static function ingoreInactiveCard($listCard, $type): array
    {
        $listCardInactive = CardListModel::whereActive(0)->whereType($type)->get()->toArray();
        $listCardInactive = array_column($listCardInactive, 'name');
        foreach ($listCard as $key => $card) {
            if (in_array($key, $listCardInactive)) {
                unset($listCard[$key]);
            }
        }
        return $listCard;
    }
}
