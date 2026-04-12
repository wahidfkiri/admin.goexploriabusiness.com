<?php

namespace Vendor\Project\Mail;

use App\Models\Task;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TaskUpdated extends Mailable
{
    use Queueable, SerializesModels;

    public Task $task;
    public User $updater;
    public array $changes;
    public string $taskUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(Task $task, User $updater, array $changes = [])
    {
        $this->task = $task;
        $this->updater = $updater;
        $this->changes = $changes;
        $this->taskUrl = route('tasks.show', $task->id);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Tâche mise à jour: ' . $this->task->name,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'project::emails.task-updated',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}