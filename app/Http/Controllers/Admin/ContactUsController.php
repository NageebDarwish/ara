<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Admin\{ContactUsRepository};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\ContactUs;

class ContactUsController extends Controller
{
    protected $repository;

    public function __construct(ContactUsRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request)
    {
        $filter = $request->get('filter', 'all');
        $data = $this->repository->all($filter);

        return view('admin.modules.contactus.index', compact('data', 'filter'));
    }

    public function destroy($id)
    {
        $this->repository->delete($id);

        return redirect()->route('admin.contactus.index')->with('success', 'Deleted successfully.');
    }

    public function reply(Request $request)
    {
        $request->validate([
            'message_id' => 'required|exists:contact_us,id',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'send_copy' => 'boolean',
            'mark_as_replied' => 'boolean'
        ]);

        try {
            // Get the original message
            $originalMessage = ContactUs::findOrFail($request->message_id);

            // Send email reply
            Mail::send('emails.contact-reply', [
                'recipientName' => $originalMessage->name,
                'replyMessage' => $request->message,
                'originalSubject' => $originalMessage->subject,
                'originalMessage' => $originalMessage->message
            ], function ($mail) use ($originalMessage, $request) {
                $mail->to($originalMessage->email, $originalMessage->name)
                    ->subject($request->subject)
                    ->from(config('mail.from.address'), config('mail.from.name'));
            });

            // Send copy to admin if requested
            if ($request->send_copy) {
                Mail::send('emails.contact-reply-copy', [
                    'recipientName' => $originalMessage->name,
                    'recipientEmail' => $originalMessage->email,
                    'replyMessage' => $request->message,
                    'originalSubject' => $originalMessage->subject,
                    'originalMessage' => $originalMessage->message
                ], function ($mail) use ($originalMessage, $request) {
                    $mail->to(config('mail.from.address'))
                        ->subject('Copy: ' . $request->subject)
                        ->from(config('mail.from.address'), config('mail.from.name'));
                });
            }

            // Mark as replied if requested
            if ($request->mark_as_replied) {
                $originalMessage->update(['replied_at' => now()]);
            }

            $originalMessage->update(['read_at' => now()]);

            return response()->json([
                'success' => true,
                'message' => 'Reply sent successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send reply: ' . $e->getMessage()
            ], 500);
        }
    }

    public function markAsRead($id)
    {
        try {
            $this->repository->markAsRead($id);

            return response()->json([
                'success' => true,
                'message' => 'Message marked as read successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark as read: ' . $e->getMessage()
            ], 500);
        }
    }
}