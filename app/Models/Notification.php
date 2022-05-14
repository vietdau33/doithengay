<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notification';

    /**
     * @throws Exception
     */
    public static function setNotification(string $notif, $callByMulti = false): self|array
    {
        try {
            $notif = str_replace(array("\r\n", "\r"), "", $notif);
            $notif = preg_replace('/\s\s+/', ' ', $notif);
            $contentAlias = explode(' ', $notif);
            $alias = array_slice($contentAlias, 0, 5);
            $contentAlias = convert_vi_to_en(implode('_', $contentAlias));
            $alias = convert_vi_to_en(implode('_', $alias));
            $contentAlias = strtolower($contentAlias);
            $alias = strtolower($alias);

            if (self::whereContentAlias($contentAlias)->first() != null) {
                $mgs = 'Thông báo này đã tồn tại trên hệ thống!';
                if ($callByMulti) {
                    throw new Exception($mgs);
                }
                return [
                    "success" => false,
                    "message" => $mgs
                ];
            }

            if (self::whereAlias($alias)->first() != null) {
                $alias .= '_' . strtotime(now());
            }

            $new_notif = new self;
            $new_notif->alias = strtolower($alias);
            $new_notif->user_id = user()->id;
            $new_notif->content_alias = $contentAlias;
            $new_notif->content = $notif;
            $new_notif->order = 999999;
            $new_notif->save();

            return $new_notif;
        } catch (Exception $exception) {
            if ($callByMulti) {
                throw $exception;
            }
            return [
                "success" => false,
                "message" => $exception->getMessage()
            ];
        }
    }

    /**
     * @throws Exception
     */
    public static function setMultiNotification(array $arrNotif): array
    {
        try {
            $result = [];
            foreach ($arrNotif as $notif) {
                $result[$notif] = self::setNotification($notif, true);
            }
            return [
                'success' => true,
                'datas' => $result
            ];
        } catch (Exception $exception) {
            return [
                "success" => false,
                "message" => $exception->getMessage()
            ];
        }
    }

    public static function getNotification($getAll = false): Collection
    {
        $collection = self::orderBy('order');
        if (!$getAll) {
            $collection->whereActive(1);
        }
        return $collection->get();
    }

    public static function buildNotificationShow(): string
    {
        $separatorNotif = SystemSetting::getSetting('separator_notification');
        $separatorNotif = "<span class='space'></span>$separatorNotif<span class='space'></span>";
        $notification = Notification::getNotification()->toArray();
        $notification = array_map('htmlspecialchars', array_column($notification, 'content'));
        return implode($separatorNotif, $notification);
    }
}
