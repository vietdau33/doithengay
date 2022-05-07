<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $table = 'report';

    public static function setReport($name, $type, $content): void
    {
        $report = self::whereName($name)->whereType($type)->first();
        if ($report instanceof Report) {
            $report->content = $content;
            $report->save();
            return;
        }

        $report = new self;
        $report->name = $name;
        $report->type = $type;
        $report->content = $content;
        $report->save();
    }

    public static function getReports(): array
    {
        $reports = [];
        foreach (self::all()->toArray() as $report){
            $type = explode('.', $report['type']);
            eval('$reports[$report[\'name\']]["' . implode('"]["', $type) . '"] = $report[\'content\'];');
        }
        return $reports;
    }
}
