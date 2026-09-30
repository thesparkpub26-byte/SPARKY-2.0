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
    /** Public: subscribe an email address. Subscribing again after leaving simply re-joins. */
    public function subscribe(Request $request)
    {
        $validated = $request->validate(['email' => 'required|email|max:255']);

        return $this->join(strtolower(trim($validated['email'])));
    }

    /** Signed in: is my account's email on the newsletter? (drives the Subscribe / Unsubscribe button) */
    public function status(Request $request)
    {
        return response()->json(['subscribed' => $this->activeSubscription($request->user()->email)->exists()]);
    }

    /** Signed in: subscribe my account's email. */
    public function subscribeMe(Request $request)
    {
        return $this->join(strtolower(trim($request->user()->email)));
    }

    /** Signed in: leave the newsletter (the Subscribe button brings it back). */
    public function unsubscribeMe(Request $request)
    {
        $subscriber = $this->activeSubscription($request->user()->email)->first();
        $subscriber?->update(['unsubscribed_at' => now()]);

        return response()->json([
            'subscribed' => false,
            'message'    => "You've been unsubscribed and won't receive our newsletter anymore.",
        ]);
    }

    private function activeSubscription(string $email)
    {
        return NewsletterSubscriber::active()->where('email', strtolower(trim($email)));
    }

    private function join(string $email)
    {
        $subscriber = NewsletterSubscriber::firstOrNew(['email' => $email]);
        if ($subscriber->exists && !$subscriber->unsubscribed_at) {
            return response()->json(['subscribed' => true, 'message' => "You're already subscribed. Thank you!"]);
        }

        $subscriber->unsubscribe_token = $subscriber->unsubscribe_token ?: Str::random(48);
        $subscriber->unsubscribed_at = null;
        $subscriber->save();

        try {
            Mail::to($subscriber->email)->send(new NewsletterWelcomeMail($subscriber));
        } catch (\Throwable $e) {
            Log::error('Newsletter welcome email failed for subscriber ' . $subscriber->id . ': ' . $e->getMessage());
        }

        return response()->json([
            'subscribed' => true,
            'message'    => "Thank you for subscribing! We'll send you the latest from TheSPARK.",
        ], 201);
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
