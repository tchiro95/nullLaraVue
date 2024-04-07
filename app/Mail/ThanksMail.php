<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Queue\SerializesModels;

class ThanksMail extends Mailable
{
  use Queueable, SerializesModels;

  /**
   * Create a new message instance.
   *
   * @return void
   */
  public $products;
  public $user;
  public $username;

  public function __construct($products, $user)
  {
    //
    $this->products = $products;
    $this->user = $user;
  }

  /**
   * Get the message envelope.
   *
   * @return \Illuminate\Mail\Mailables\Envelope
   */
  public function envelope()
  {
    return new Envelope(
      from: new Address('info@nullarbor.co.jp', 'nullarborpress'),
      subject: 'ご購入ありがとうございました。',
    );
  }

  /**
   * Get the message content definition.
   *
   * @return \Illuminate\Mail\Mailables\Content
   */
  public function content()
  {
    return new Content(
      view: 'emails.thanks',
    );
  }

  /**
   * Get the attachments for the message.
   *
   * @return array
   */
  public function attachments()
  {
    return [];
  }
}
