<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'subject',
        'html_content',
        'variables',
        'trigger_event',
        'is_active',
        'description',
    ];

    protected $casts = [
        'variables' => 'array',
        'is_active' => 'boolean',
    ];

    public function emailLogs()
    {
        return $this->hasMany(EmailLog::class);
    }

    /**
     * Replace variables in the template with actual values
     */
    public function render($data = [])
    {
        $content = $this->html_content;

        foreach ($data as $key => $value) {
            $content = str_replace('[' . strtoupper($key) . ']', $value, $content);
        }

        return $content;
    }
}
