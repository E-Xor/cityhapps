<?php

namespace CityHapps\Jobs;

use CityHapps\User;
use CityHapps\Happ;
use CityHapps\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendEventEmail extends Job implements SelfHandling, ShouldQueue
{
    protected $happ;
    use InteractsWithQueue, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Happ $happ)
    {
        $this->happ = $happ;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $happ = $this->happ;
        foreach ($happ->categories as $category)
        {
                error_log('ooooooooooo');
            $user_categories = \UserCategory::where('category_id', '=', $category->id)->get();
            foreach ($user_categories as $uc)
            {
                $user = User::where('id', '=', $uc->user_id)->get();
                error_log($user->email);
                Mail::raw('new Event added to your favorite category', function($message)
                {
                    $message->from('team@cityhapps.com', 'CityHapps');
                    $message->to($user->email)->cc('zaali@live.com');
                });
            }
        }
    }
}
