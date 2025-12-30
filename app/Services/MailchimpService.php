<?php

namespace App\Services;

use DrewM\MailChimp\MailChimp;
use Illuminate\Support\Facades\Log;
use Exception;

class MailchimpService
{
    protected $mailchimp;
    protected $listId;

    public function __construct()
    {
        $apiKey = config('services.mailchimp.key');
        $this->listId = config('services.mailchimp.list_id');

        if ($apiKey) {
            try {
                $this->mailchimp = new MailChimp($apiKey);
            } catch (Exception $e) {
                Log::error('Mailchimp Initialization Error: ' . $e->getMessage());
            }
        }
    }

    public function sendCampaign($subject, $content, $replyTo, $fromName)
    {
        if (!$this->mailchimp) return false;

        try {
            // 1. Create a Campaign
            $campaign = $this->mailchimp->post("campaigns", [
                'type' => 'regular',
                'recipients' => ['list_id' => $this->listId],
                'settings' => [
                    'subject_line' => $subject,
                    'reply_to' => $replyTo,
                    'from_name' => $fromName,
                ]
            ]);

            if (!$this->mailchimp->success()) {
                Log::error('Mailchimp Campaign Error: ' . $this->mailchimp->getLastError());
                return false;
            }

            $campaignId = $campaign['id'];

            // 2. Set Campaign Content
            $this->mailchimp->put("campaigns/$campaignId/content", [
                'html' => $content
            ]);

            // 3. Send the Campaign
            $this->mailchimp->post("campaigns/$campaignId/actions/send");

            return $this->mailchimp->success();
        } catch (Exception $e) {
            Log::error('Mailchimp Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Mailchimp is primarily for bulk, but we can use transactional (Mandrill) 
     * or just standard Laravel Mail for simple reminders if preferred.
     * For now, following user request for Mailchimp API.
     */
}
