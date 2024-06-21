<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Carbon\Carbon;

class SendProductSoldNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $purchasable;

    /**
     * Create a new notification instance.
     *
     * @param mixed $purchasable
     * @param string $productName
     */
    public function __construct($purchasable, protected $productName, protected $customer)
    {
        $this->purchasable = $purchasable;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Payment Successful - Access Your Purchase Now')
            ->view('emails.ninety_plus_template', [
                'notifiable' => $notifiable,
                'content' => $this->generateContent($notifiable),
            ]);
    }

    /**
     * Generate the notification content.
     *
     * @param object $notifiable
     * @return string
     */
    protected function generateContent(object $notifiable): string
    {
        return sprintf(
            '
             We are delighted to inform you that %s student is bought your %s %s.

            Here are the details of your Invoice:

            <br><br>
            <b>Item:</b> %s<br>
            <b>Description:</b> %s<br>
            <b>Amount:</b> $%s<br>
            <b>Date:</b> %s<br><br>

            If you have any questions or need further assistance, please do not hesitate to contact our support team.

            Thank you for choosing us!

            Best regards,
            Ninety Plus',
            $this->customer->full_name,
            $this->purchasable?->name ?? $this->purchasable?->title,
            $this->productName,
            $this->productName,
            $this->purchasable->description ?? 'N/A',
            number_format($this->purchasable->price, 2),
            Carbon::now()->format('F j, Y, g:i a')
        );
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [];
    }
}
