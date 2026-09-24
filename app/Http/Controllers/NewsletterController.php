<?php

namespace App\Http\Controllers;

use App\Mail\NewsletterWelcomeMail;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class NewsletterController extends Controller
{
    /** Public: the "Subscribe to our newsletter" form. Subscribing again after leaving simply re-joins. */
    public function subscribe(Request $request)
    {
        $validated = $request->validate(['email' => 'required|email|max:255']);
        $email = strtolower(trim($validated['email']));

        $subscriber = NewsletterSubscriber::firstOrNew(['email' => $email]);
        if ($subscriber->exists && !$subscriber->unsubscribed_at) {
            return response()->json(['message' => "You're already subscribed. Thank you!"]);
        }

        $subscriber->unsubscribe_token = $subscriber->unsubscribe_token ?: Str::random(48);
        $subscriber->unsubscribed_at = null;
        $subscriber->save();

        try {
            Mail::to($subscriber->email)->send(new NewsletterWelcomeMail($subscriber));
        } catch (\Throwable $e) {
            Log::error('Newsletter welcome email failed for subscriber ' . $subscriber->id . ': ' . $e->getMessage());
        }

        return response()->json(['message' => "Thank you for subscribing! We'll send you the latest from TheSPARK."], 201);
    }

    /** Public: the page behind the unsubscribe link in every newsletter email. */
    public function unsubscribe(Request $request)
    {
        $validated = $request->validate(['token' => 'required|string|max:64']);

        $subscriber = NewsletterSubscriber::where('unsubscribe_token', $validated['token'])->first();
        if (!$subscriber) {
            return response()->json(['message' => 'This unsubscribe link is not valid.'], 404);
        }

        if (!$subscriber->unsubscribed_at) {
            $subscriber->update(['unsubscribed_at' => now()]);
        }

        return response()->json(['message' => "You've been unsubscribed and won't receive our newsletter anymore."]);
    }

    /** Admin / EIC: who is subscribed. */
    public function index()
    {
        $active = NewsletterSubscriber::active()->latest()->get(['id', 'email', 'created_at']);

        return response()->json([
            'active'      => $active->count(),
            'unsubscribed' => NewsletterSubscriber::whereNotNull('unsubscribed_at')->count(),
            'subscribers' => $active,
        ]);
    }
}
