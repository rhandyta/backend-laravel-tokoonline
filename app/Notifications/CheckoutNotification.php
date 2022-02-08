<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CheckoutNotification extends Notification implements ShouldQueue
{
    use Queueable;
    protected $detailTransactions;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($detailTransactions)
    {
        $this->detailTransactions = $detailTransactions;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->greeting('Checkout')
            ->subject('Checkout ' . $this->detailTransactions->order_number)
            ->from('sendingemail117@gmail.com', 'STORE TORREN')
            ->line(
                'Name: ' . $notifiable->name . "\r\n" .
                    'Phone Number: ' . $notifiable->phone . "\r\n" .
                    'Address: ' . $notifiable->address . "\r\n" .
                    'Email: ' . $notifiable->email . "\r\n"
            )
            ->line(
                'Order Number: ' . $this->detailTransactions->order_number . "\r\n" .
                    'Product: ' . $this->detailTransactions->product->name_product . "\r\n" .
                    'Quantity: ' . $this->detailTransactions->quantity . "\r\n" .
                    'Price: ' . $this->detailTransactions->price . "\r\n" .
                    'Total: ' . $this->detailTransactions->total . "\r\n" .
                    'Date: ' . $this->detailTransactions->created_at
            );
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
