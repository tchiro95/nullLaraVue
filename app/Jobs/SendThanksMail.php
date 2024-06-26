<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\ThanksMail;


class sendThanksMail implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  public $products;
  public $user;

  /**
   * Create a new job instance.
   *
   * @return void
   */
  public function __construct($products, $user)
  {
    //
    $this->products = $products;
    $this->user =  $user;
  }

  /**
   * Execute the job.
   *
   * @return void
   */
  public function handle()
  {
    //

    Mail::to($this->user->email)
      ->send(new ThanksMail($this->products, $this->user));
  }
}
