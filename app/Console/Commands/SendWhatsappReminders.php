<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\booking\Booking;
use App\Models\setting\SystemSetting;
use App\Services\WhatsappService;
use Carbon\Carbon;

class SendWhatsappReminders extends Command
{
    protected $signature = 'whatsapp:reminders';
    protected $description = 'Send automatic WhatsApp reminders';

    public function handle()
    {
        $whatsapp = new WhatsappService();

        $today = Carbon::today()->toDateString();
        $tomorrow = Carbon::tomorrow()->toDateString();
        $yesterday = Carbon::yesterday()->toDateString();

        $allBookings = Booking::whereDate('checkin_date', '>=', $yesterday)
            ->orWhereDate('checkout_date', $yesterday)
            ->get();

        foreach ($allBookings as $booking) {
            // 1. Booking Confirmation (on creation - you might want to handle it on create)
            if ($booking->status == '4') {
                $whatsapp->sendText($booking, "booking_confirmation");
            }

            // 2. Check-in Reminder
            if ($booking->checkin_date == $tomorrow) {
                $whatsapp->sendText($booking, "checkin_reminder");
            }

            // 3. Payment Reminder
            if ($booking->payment_status === '0' || $booking->payment_status === null && $booking->checkin_date > $today) {
                $whatsapp->sendText($booking, "payment_reminder");
            }

            // 4. Checkout Reminder (just before checkout)
            if ($booking->checkout_date == $yesterday) {
                $whatsapp->sendText($booking, "checkout_reminder");
            }

            // 5. Thank You (post checkout)
            if ($booking->checkout_date == $yesterday) {
                $whatsapp->sendText($booking, "thank_you");
            }

            // 6. Cancellation Confirmation
            if ($booking->booking_status == '3' && $booking->updated_at->isToday()) {
                $whatsapp->sendText($booking, "cancellation_confirmation");
            }
        }

        $this->info('WhatsApp reminders sent for all applicable templates.');
    }
}
