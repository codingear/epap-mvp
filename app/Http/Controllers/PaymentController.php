<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function checkout(Course $course)
    {
        $user = Auth::user();
        
        // Check if user already purchased this course
        if ($user->hasPurchasedCourse($course->id)) {
            return redirect()->route('courses.show', $course)
                           ->with('info', 'Ya tienes acceso a este curso.');
        }

        return view('payments.checkout', compact('course'));
    }

    public function processPayment(Request $request, Course $course)
    {
        $request->validate([
            'payment_method' => 'required|string',
            'card_number' => 'required_if:payment_method,card|string',
            'card_holder' => 'required_if:payment_method,card|string',
            'expiry_month' => 'required_if:payment_method,card|string',
            'expiry_year' => 'required_if:payment_method,card|string',
            'cvv' => 'required_if:payment_method,card|string',
        ]);

        $user = Auth::user();

        try {
            DB::beginTransaction();

            // Create payment record
            $payment = Payment::create([
                'user_id' => $user->id,
                'course_id' => $course->id,
                'amount' => $course->price,
                'currency' => $course->currency,
                'payment_method' => $request->payment_method,
                'status' => 'pending'
            ]);

            // Process payment with dlocal
            $dlocalResponse = $this->processDlocalPayment($payment, $request);

            if ($dlocalResponse['success']) {
                $payment->update([
                    'dlocal_payment_id' => $dlocalResponse['payment_id'],
                    'dlocal_transaction_id' => $dlocalResponse['transaction_id'],
                    'status' => 'completed',
                    'payment_data' => $dlocalResponse['data'],
                    'paid_at' => now()
                ]);

                // Enroll user in course
                $this->enrollUserInCourse($user, $course);

                DB::commit();

                return redirect()->route('courses.show', $course)
                               ->with('success', '¡Pago completado exitosamente! Ya tienes acceso al curso.');
            } else {
                $payment->update([
                    'status' => 'failed',
                    'payment_data' => $dlocalResponse['data']
                ]);

                DB::rollback();

                return back()->with('error', 'Error en el pago: ' . $dlocalResponse['message']);
            }

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Payment processing error: ' . $e->getMessage());
            return back()->with('error', 'Error procesando el pago. Inténtalo de nuevo.');
        }
    }

    private function processDlocalPayment($payment, $request)
    {
        // Simulated dlocal integration - replace with actual dlocal API calls
        try {
            // This would be the actual dlocal API integration
            $dlocal_config = [
                'api_key' => env('DLOCAL_API_KEY'),
                'secret_key' => env('DLOCAL_SECRET_KEY'),
                'environment' => env('DLOCAL_ENVIRONMENT', 'sandbox'),
                'base_url' => env('DLOCAL_ENVIRONMENT', 'sandbox') === 'production' 
                    ? 'https://api.dlocal.com' 
                    : 'https://sandbox.dlocal.com'
            ];

            $paymentData = [
                'amount' => $payment->amount * 100, // dlocal expects cents
                'currency' => $payment->currency,
                'country' => 'MX', // Mexico
                'payment_method_id' => $request->payment_method === 'card' ? 'CARD' : strtoupper($request->payment_method),
                'payment_method_flow' => 'DIRECT',
                'payer' => [
                    'name' => $payment->user->name,
                    'email' => $payment->user->email,
                    'document' => '12345678', // This should come from user profile
                    'user_reference' => (string) $payment->user->id,
                    'address' => [
                        'country' => $payment->user->country ?? 'MX',
                        'state' => $payment->user->state ?? '',
                        'city' => $payment->user->city ?? '',
                        'zip_code' => '12345',
                        'street' => 'Calle Principal 123'
                    ]
                ],
                'card' => $request->payment_method === 'card' ? [
                    'holder_name' => $request->card_holder,
                    'number' => $request->card_number,
                    'cvv' => $request->cvv,
                    'expiry_month' => $request->expiry_month,
                    'expiry_year' => $request->expiry_year
                ] : null,
                'order_id' => 'EPAP-' . $payment->id . '-' . time(),
                'description' => 'Curso: ' . $payment->course->title,
                'notification_url' => route('payment.webhook'),
            ];

            // For now, simulate successful payment
            // TODO: Replace with actual dlocal API call
            $response = $this->simulateDlocalResponse($payment);

            return $response;

        } catch (\Exception $e) {
            Log::error('Dlocal API error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error de conexión con el procesador de pagos',
                'data' => ['error' => $e->getMessage()]
            ];
        }
    }

    private function simulateDlocalResponse($payment)
    {
        // Simulate dlocal response - remove this in production
        return [
            'success' => true,
            'payment_id' => 'DL_' . Str::random(16),
            'transaction_id' => 'TXN_' . Str::random(12),
            'message' => 'Payment successful',
            'data' => [
                'status' => 'PAID',
                'amount' => $payment->amount * 100,
                'currency' => $payment->currency,
                'created_date' => now()->toISOString()
            ]
        ];
    }

    private function enrollUserInCourse($user, $course)
    {
        // Add user to course_user table if not already enrolled
        if (!$course->students()->where('user_id', $user->id)->exists()) {
            $course->students()->attach($user->id, [
                'completed' => false,
                'progress' => 0,
                'last_accessed_at' => now()
            ]);
        }
    }

    public function webhook(Request $request)
    {
        // Handle dlocal webhooks for payment status updates
        try {
            $payload = $request->all();
            Log::info('Dlocal webhook received: ', $payload);

            // Verify webhook signature (implement based on dlocal docs)
            if (!$this->verifyWebhookSignature($request)) {
                return response('Unauthorized', 401);
            }

            $paymentId = $payload['id'] ?? null;
            $status = $payload['status'] ?? null;

            if ($paymentId && $status) {
                $payment = Payment::where('dlocal_payment_id', $paymentId)->first();
                
                if ($payment) {
                    $payment->update([
                        'status' => $this->mapDlocalStatus($status),
                        'payment_data' => $payload
                    ]);

                    if ($status === 'PAID' && $payment->status === 'completed') {
                        $this->enrollUserInCourse($payment->user, $payment->course);
                    }
                }
            }

            return response('OK', 200);

        } catch (\Exception $e) {
            Log::error('Webhook processing error: ' . $e->getMessage());
            return response('Error', 500);
        }
    }

    private function verifyWebhookSignature($request)
    {
        // Implement dlocal webhook signature verification
        // This is a placeholder - implement according to dlocal documentation
        return true;
    }

    private function mapDlocalStatus($dlocalStatus)
    {
        $statusMap = [
            'PAID' => 'completed',
            'PENDING' => 'pending',
            'REJECTED' => 'failed',
            'CANCELLED' => 'failed',
        ];

        return $statusMap[$dlocalStatus] ?? 'pending';
    }

    public function success(Payment $payment)
    {
        if ($payment->user_id !== Auth::id()) {
            abort(403);
        }

        return view('payments.success', compact('payment'));
    }

    public function history()
    {
        $payments = Auth::user()->payments()
                              ->with('course')
                              ->orderBy('created_at', 'desc')
                              ->paginate(10);

        return view('payments.history', compact('payments'));
    }
}
