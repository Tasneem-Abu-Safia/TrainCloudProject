<?php

namespace App\Mail;

use App\Models\Course;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TrainingNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $subject;
    public Course $course;
    public $content;

    /**
     * Create a new message instance.
     *
     * @param string $subject
     * @param string $course
     * @param string $content
     * @return void
     */
    public function __construct($subject, $content, Course $course)
    {
        $this->subject = $subject;
        $this->course = $course;
        $this->content = $content;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.trainingNotification')
            ->subject($this->subject)
            ->with([
                'course' => $this->course,
                'content' => $this->content,
            ]);
    }
}
